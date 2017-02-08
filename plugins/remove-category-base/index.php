<?php
/*
Plugin Name: Remove Category Base
Plugin URI: http://donkey.name/wordpress-plugins/remove-category-base/
Description: 去掉分类链接中的前缀。如“category/”、“archives/category/”等。
Version: 1.0
Author: 骑驴觅驴
Author URI: http://donkey.name/
*/

defined('ABSPATH') or die('http://donkey.name');

/**
 * 插件激活时调用 flush_rewrite_rules 函数重置重写规则
 */
register_activation_hook(__FILE__, 'flush_rewrite_rules');

/**
 * 插件停用时移除过滤器并重置重写规则
 */
register_deactivation_hook(__FILE__, 'donkey_no_category_base_deactivate');
/**
 * 插件停用的回调函数
 */
function donkey_no_category_base_deactivate()
{
	remove_filter('category_rewrite_rules', 'donkey_category_rewrite_rules');
	delete_option('donkey_original_category_rewrite_rules');
	flush_rewrite_rules(true);
}

/**
 * 分类添加/编辑/删除后执行的动作
 * flush_rewrite_rules 函数将删除重写规则，并随之重新创建重写规则
 */
add_action('created_category', 'flush_rewrite_rules');
add_action('edited_category', 'flush_rewrite_rules');
add_action('delete_category', 'flush_rewrite_rules');

/**
 * 请求解析过滤器
 */
add_action('parse_request', 'donkey_parse_request');
/**
 * {@link parse_request} 过滤器的回调函数。
 * 如果访问的分类URL是原来的结构，则重定向到新的分类URL
 * 
 * @param object $request
 * @return void
 */
function donkey_parse_request($request)
{
	// 判断是否原来的分类重写规则
	if (isset($request->matched_rule)
			&& array_key_exists($request->matched_rule, 
					donkey_get_original_category_rewrite_rules())) {
		if (isset($request->query_vars['category_name'])
				&& !empty($request->query_vars['category_name'])) {
			// 获取新的分类URL
			$termlink = get_term_link($request->query_vars['category_name'], 'category');
			if (is_string($termlink)) {
				wp_redirect($termlink, 301);
				exit;
			}
		}
	}
}
/**
 * 分类重写规则过滤器
 */
add_filter('category_rewrite_rules', 'donkey_category_rewrite_rules');
/**
 * {@link category_rewrite_rules} 过滤器的回调函数。
 * 为每一分类都生成重写规则，以达到去除分类URL前缀的目的。
 * 我们保留了原来的分类重写规则，便于处理将原始分类URL跳转到去除前缀后的分类URL。
 * 
 * @param array $rewrite_rules
 * @return array
 */
function donkey_category_rewrite_rules($rewrite_rules)
{
	global $wp_rewrite;

	update_option('donkey_original_category_rewrite_rules', $rewrite_rules);

	$category_rewrite_rules = array();
	$categories = get_categories('hide_empty=0');
	foreach ($categories as $category) {
		$rules = donkey_generate_category_rule($category);
		$category_rewrite_rules = array_merge($category_rewrite_rules, $rules);
	}
	
	// 保留原来的分类重写规则
 	return array_merge($category_rewrite_rules, $rewrite_rules);
}

/**
 * 根据分类对象生成分类重写规则
 *
 * @param object $category 分类对象
 * @return array 返回单个分类重写规则
 */
function donkey_generate_category_rule($category)
{
	$rules = array();
 	$category_slug = str_replace(get_home_url(), '', get_term_link($category));
 	$rewrite_rules = donkey_get_original_category_rewrite_rules();

	foreach ($rewrite_rules as $match => $query) {
		$match = str_replace(donkey_get_category_regex(), '(' . trim($category_slug, '/') . ')', $match);
		$rules[$match] = $query;
	}

	return $rules;
}

/**
 * 分类链接过滤器
 * 我们期望在回调函数{@link donkey_category_link}中生成符合分类重写规则的URL
 */
add_filter('category_link', 'donkey_category_link');
/**
 * {@link category_link} 过滤器的回调函数。
 * 去除分类URL中的前缀，生成符合分类重写规则的URL
 * 
 * @param string $termlink
 * @param string $term_id
 * @return string 返回符合分类重写规则的URL
 */
function donkey_category_link($termlink = '', $term_id = null)
{
	$termlink = str_replace(donkey_get_category_prefix(), '', $termlink);
	
	return $termlink;
}

/**
 * 返回默认的分类重写规则
 * 
 * @return array
 */
function donkey_get_original_category_rewrite_rules()
{
	return get_option('donkey_original_category_rewrite_rules', array());
}

/**
 * 返回分类的重写正则
 * 
 * @return string
 */
function donkey_get_category_regex()
{
	static $regex = null;
	
	if (null === $regex) {
		global $wp_rewrite;
	
		$regex = str_replace('%category%', 
				donkey_get_category_rewritereplace(),
				$wp_rewrite->get_category_permastruct());
	
		// 去除开头的反斜杠
		$regex = preg_replace('|^/+|', '', $regex);
	}
	
	return (string) $regex;
}

/**
 * 返回重写规则中分类占位符的正则
 * 分类占位符的正则受是否分层等因素影响，
 * 取值是一般是 (.+?) 和 (.+?)，默认是 (.+?)。
 * 具体请参看 {@link register_taxonomy} 函数
 * 
 * @return string
 */
function donkey_get_category_rewritereplace()
{
	static $regex = null;
	
	if (null === $regex) {
		global $wp_rewrite;
	
		$pos = array_search('%category%', $wp_rewrite->rewritecode);
		if (false !== $pos && null !== $pos) {
			$regex = $wp_rewrite->rewritereplace[$pos];
		} else {
			$regex = '(.+?)';
		}
	}
	
	return (string) $regex;
}

/**
 * 返回分类URL的前缀，此前缀受URL固定链接影响
 * 
 * @return string
 */
function donkey_get_category_prefix()
{
	static $prefix = null;
	
	if (null === $prefix) {
		$prefix = str_replace(donkey_get_category_rewritereplace(), '', donkey_get_category_regex());
	}
	
	return (string) $prefix;
}