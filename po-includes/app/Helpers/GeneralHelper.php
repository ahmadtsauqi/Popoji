<?php

use App\Setting;
use App\Theme;
use App\Menu;
use App\Pages;
use App\Post;
use App\Category;

if (!function_exists('getPicture')) {
	function getPicture($name, $type, $user)
    {
		if ($type == 'medium') {
			if (file_exists('po-content/uploads/medium/medium_'.$name)) {
				return asset('po-content/uploads/medium/medium_'.$name);
			} else {
				return asset('po-content/uploads/users/user-'.$user.'/medium/medium_'.$name);
			}
		} elseif ($type == 'thumb') {
			if (file_exists('po-content/thumbs/'.$name)) {
				return asset('po-content/thumbs/'.$name);
			} else {
				return asset('po-content/thumbs/users/user-'.$user.'/'.$name);
			}
		} else {
			if (file_exists('po-content/uploads/'.$name)) {
				return asset('po-content/uploads/'.$name);
			} else {
				return asset('po-content/uploads/users/user-'.$user.'/'.$name);
			}
		}
	}
}

if (!function_exists('getFile')) {
	function getFile($name, $user)
    {
		if (file_exists('po-content/uploads/'.$name)) {
			return asset('po-content/uploads/'.$name);
		} else {
			return asset('po-content/uploads/users/user-'.$user.'/'.$name);
		}
	}
}

if (!function_exists('getSetting')) {
    function getSetting($options)
    {
		$result = Setting::where('options', $options)->first();
		if ($result) {
			return $result->value;
		} else {
			return '';
		}
	}
}

if (!function_exists('getTheme')) {
    function getTheme($files)
    {
		$result = Theme::where('active', 'Y')->first();
		if ($result) {
			return 'frontend.'.$result->folder.'.'.$files;
		} else {
			$resultdef = Theme::where('id', '1')->first();
			return 'frontend.'.$resultdef->folder.'.'.$files;
		}
	}
}

if (!function_exists('getSettingGroup')) {
    function getSettingGroup($groups)
    {
		$result = Setting::where('groups', $groups)->orderBy('id', 'asc')->get();
		if ($result) {
			return $result;
		} else {
			return [];
		}
	}
}

if (!function_exists('categoryTreeOption')) {
    function categoryTreeOption(array $elements, $parentId = 0, $indent = '')
    {
		foreach ($elements as $key => $element) {
			if ($element['parent'] == $parentId) {
				echo '<option value="'.$element['id'].'">'.$indent.' '.$element['title'].'</option>';
				
				$children = categoryTreeOption($elements, $element['id'], $indent.'-');
			}
		}
	}
}

if (!function_exists('getMenus')) {
    function getMenus()
    {
		$menus = new Menu;
		return $menus->tree();
	}
}

if (!function_exists('getPages')) {
    function getPages($id)
    {
		$result = Pages::where('id', $id)->first();
		return $result;
	}
}

if (!function_exists('getCategory')) {
    function getCategory($limit)
    {
		$result = Category::select('id', 'title', 'seotitle')->where('active', '=', 'Y')->limit($limit)->orderBy('id', 'ASC')->withCount('posts')->get();
		return $result;
	}
}

if (!function_exists('latestPost')) {
	function latestPost($limit, $offset = '0')
	{
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([['posts.active', '=', 'Y']])
			->select('posts.*', 'categories.title as ctitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('headlinePost')) {
	function headlinePost($limit, $offset = '0')
	{
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([['posts.active', '=', 'Y'],['posts.headline', '=', 'Y']])
			->select('posts.*', 'categories.title as ctitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}
