<?php  /* UserController php file */
namespace admin;
class SitemapController extends BaseAdminController {


	function sitemap() {
	  $this->f3->set('SESSION.csrf_old', $this->f3->CSRF);
	/*  Список всех  постов */
    $site_map = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" ;
	 $site_map .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" ;
	$model = new \Blogpost($this->database);
    $posts = $model ->all();

    /*  Список всех страниц  */
	$model = new \Page($this->database);
	$pages = $model ->all();
   
    
	foreach($posts as $post){
		$site_map .= '<url>'. "\n" ; 
		$site_map .=   '<loc>http://www.'. $this->f3->get('HOST') . '/blog/post/' .  $post['id']    .  '</loc>'. "\n" ;
		$site_map .=   '<lastmod>' . $post['changed']  . '</lastmod>'. "\n" ;
		$site_map .=   '<changefreq>monthly</changefreq>'. "\n" ;
		$site_map .=   '<priority>1</priority>'. "\n" ;
		$site_map .= '</url>'. "\n" ;
	}
    foreach($pages as $page){
		$site_map .= '<url>'. "\n" ;  
		$site_map .=   '<loc>http://www.'. $this->f3->get('HOST') . '/page/' .  $page['id']    .  '</loc>'. "\n" ;
		$site_map .=   '<lastmod>' . $page['changed']  . '</lastmod>'. "\n" ;
		$site_map .=   '<changefreq>monthly</changefreq>'. "\n" ;
		$site_map .=   '<priority>1</priority>'. "\n" ;
		$site_map .= '</url>'. "\n" ;
	}
		
	$site_map .= '</urlset>'. "\n" ;

    $this->f3->set('view', 'admin/templates/admin_sitemap.htm');
	$this->f3->set('site_map', $site_map);
	$template = new \Template();
    echo $template->render('admin/admin_layout.htm');

    }
	function sitemapsave() {
	   $this->checkForCSRFAttack();
	   $this->f3->write("sitemap.xml", $_POST['sitemap']);
       $this->f3->reroute('@sitemap');
	}
}
