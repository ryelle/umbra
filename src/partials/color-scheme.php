<?php global $umbra_scheme; ?>
<style type="text/css">
body {
	background: <?php echo $umbra_scheme['body_background']; ?>;
}
a {
	color: <?php echo $umbra_scheme['link_color']; ?>;
}
a:hover, a:active, a:focus {
	color: <?php echo $umbra_scheme['hover_color']; ?>;
}
.entry-title,
.entry-title a {
	color: <?php echo $umbra_scheme['title_color']; ?>;
}
.site-title a {
	color: <?php echo $umbra_scheme['site_title_color']; ?>;
}
.site-description {
	color: <?php echo $umbra_scheme['description_color']; ?>;
}
.main-navigation ul li a {
	color: <?php echo $umbra_scheme['nav_text_color']; ?>;
	background-color: <?php echo $umbra_scheme['nav_bg_color']; ?>;
}
.main-navigation ul .current_page_item a,
.main-navigation ul .current-menu-item a,
.main-navigation ul li a:hover,
.main-navigation ul li a:active,
.main-navigation ul li a:focus {
	color: <?php echo $umbra_scheme['nav_text_color']; ?>;
	background-color: <?php echo $umbra_scheme['nav_current_bg_color']; ?>;
}
.site-header-bg {
	background-color: <?php echo $umbra_scheme['sidebar_bg_color']; ?>;
}
</style>
