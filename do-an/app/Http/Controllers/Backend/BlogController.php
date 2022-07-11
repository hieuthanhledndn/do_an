<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogPostCategory;
use Carbon\Carbon;
use App\Models\BlogPost;
use Image;

class BlogController extends Controller
{
    public function BlogCategory(){

    	$blogcategory = BlogPostCategory::latest()->get();
    	return view('backend.blog.category.category_view',compact('blogcategory'));
    }


  public function BlogCategoryStore(Request $request){

       $request->validate([
    		'blog_category_name' => 'required',
    		 
    	],[
    		'blog_category_name.required' => 'Hãy nhập tên danh mục',
    	]);

    	 

	BlogPostCategory::insert([
		'blog_category_name' => $request->blog_category_name,
		'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)),
		'created_at' => Carbon::now(),
		 

    	]);

	    $notification = array(
			'message' => 'Thêm bài viết thành công',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    } // end method 



    public function BlogCategoryEdit($id){

   $blogcategory = BlogPostCategory::findOrFail($id);
    	return view('backend.blog.category.category_edit',compact('blogcategory'));
    }




public function BlogCategoryUpdate(Request $request){

       $blogcar_id = $request->id;
    	 

	BlogPostCategory::findOrFail($blogcar_id)->update([
		'blog_category_name' => $request->blog_category_name,
		'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)),
		'created_at' => Carbon::now(),
		 

    	]);

	    $notification = array(
			'message' => 'Cập nhật bài viết thành công',
			'alert-type' => 'info'
		);

		return redirect()->route('blog.category')->with($notification);

    } // end method 




  ///////////////////////////// Blog Post ALL Methods //////////////////

  public function ListBlogPost(){
  	  $blogpost = BlogPost::with('category')->latest()->get();
  	  return view('backend.blog.post.post_list',compact('blogpost'));
  }


  public function AddBlogPost(){

    $blogcategory = BlogPostCategory::latest()->get();
  	$blogpost = BlogPost::latest()->get();
  	return view('backend.blog.post.post_view',compact('blogpost','blogcategory'));

  }   


  public function BlogPostStore(Request $request){

  	$request->validate([
    		'post_title' => 'required',
    		'post_image' => 'required',
    	],[
    		'post_title.required' => 'Nhập tiêu đề bài viết',
    	]);

    	$image = $request->file('post_image');
    	$name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    	Image::make($image)->resize(780,433)->save('upload/post/'.$name_gen);
    	$save_url = 'upload/post/'.$name_gen;

	BlogPost::insert([
		'category_id' => $request->category_id,
		'post_title' => $request->post_title,
		'post_slug' => strtolower(str_replace(' ', '-',$request->post_title)),
		'post_image' => $save_url,
		'post_details' => $request->post_details,
		'created_at' => Carbon::now(),

    	]);

	    $notification = array(
			'message' => 'Thêm bài viết thành công',
			'alert-type' => 'success'
		);

		return redirect()->route('list.post')->with($notification);

  } // end mehtod 



}
 