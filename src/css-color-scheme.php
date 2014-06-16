<?php global $umbra_scheme; ?>
<style type="text/css">
body {
	background: <?php echo $umbra_scheme['body-background']; ?>;
}
a {
	color: <?php echo $umbra_scheme['link-color']; ?>;
}
a:hover,
a:active,
a:focus {
	color: <?php echo $umbra_scheme['hover-color']; ?>;
}

.site-content {
	background-color: <?php echo $umbra_scheme['main-bg-color']; ?>;
}

.site-header-bg {
	background-color: <?php echo $umbra_scheme['sidebar-bg-color']; ?>;
}

.main-navigation ul li a {
	color: <?php echo $umbra_scheme['nav-text-color']; ?>;
	background-color: <?php echo $umbra_scheme['nav-bg-color']; ?>;
}

.main-navigation ul li a:hover,
.main-navigation ul li a:active,
.main-navigation ul li a:focus,
.main-navigation ul .current_page_item a,
.main-navigation ul .current_page_item a {
	color: <?php echo $umbra_scheme['nav-text-color']; ?>
	background-color: <?php echo $umbra_scheme['nav-current-bg-color']; ?>
}

.entry-title,
.entry-title a {
	color: <?php echo $umbra_scheme['header-color']; ?>;
}

.entry-meta {
	color: <?php echo $umbra_scheme['alt-color']; ?>;
}

.entry-meta a {
	color: <?php echo $umbra_scheme['highlight-color']; ?>;
}

.entry-meta a.genericon-comment {
	color: <?php echo $umbra_scheme['highlight-color']; ?>;
}

.entry-meta .genericon {
	background-color: <?php echo $umbra_scheme['sidebar_bg_color']; ?>;
}

</style>
