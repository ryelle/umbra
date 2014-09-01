<?php
/**
 * Base SCSS for the custom color schemes, exported as a
 * global variable to avoid requiring file systems access.
 */

global $umbra_base_scss;
$umbra_base_scss = <<<'SCSS'

// Base color scheme

// Use sassme to grab the current hsl of these vars, then adjust with the hue of the current color? adjust hue for alt
$base-color: saturate( $base-color, 5% );

$body-background: hsl( hue($base-color), saturation(#f9f9f9), lightness(#f9f9f9) );
$text-color: hsl( hue($base-color), saturation(#424046), lightness(#424046) );

$header-color: hsl( hue($base-color), saturation(#6b6479), lightness(#6b6479) );
$highlight-color: desaturate( hsl( hue($base-color) + 150, saturation(#e0bc64), lightness(#e0bc64) ), 20% );
$alt-color: hsl( hue($base-color), saturation(#b5b1bd), lightness(#b5b1bd) );

$main-bg-color: white;

@if ( lightness( $base-color ) > 50 ){

	$sidebar-bg-color: darken( desaturate( $base-color, 10% ), 10% );
	$site-title-color: desaturate( darken( $base-color, 30% ), 20% );
	$description-color: lighten( $sidebar-bg-color, 30% );

} @else {

	$sidebar-bg-color: $base-color;
	$site-title-color: saturate( lighten( $base-color, 30% ), 10% );
	$description-color: lighten( $sidebar-bg-color, 30% );

}

@if ( lightness( $description-color ) > 75 ){
	$comment-header-bg: $body-background;
} @else {
	$comment-header-bg: saturate( lighten( $description-color, 20% ), 20% );
}

@if ( saturation( $comment-header-bg ) > 50 ){
	$comment-text: darken( $description-color, 25% );
} @else {
	$comment-text: darken( $comment-header-bg, 25% );
}

// If no hue, let's not give these colors hue
@if ( hue( $base-color ) < 5 ) {

	$saturation: 0;
	$nav-text-color: hsl( hue($base-color), $saturation, lightness(#e9e3f4) );
	$nav-bg-color: darken( $sidebar-bg-color, 10% );
	$nav-current-bg-color: darken( $sidebar-bg-color, 15% );

} @else {

	// Main menu colors
	$saturation: saturation( #e9e3f4 );
	$nav-text-color: hsl( hue($base-color), $saturation, lightness(#e9e3f4) );
	$nav-bg-color: darken( $sidebar-bg-color, 10% );
	$nav-current-bg-color: darken( $sidebar-bg-color, 15% );

}

// Use the color scheme colors to create element-specific styles
$link-color: $alt-color;

$comment-box-border: mix( $comment-header-bg, $body-background );

@mixin hoverActiveFocus() {
	&:hover, &:active, &:focus {
		@content;
	}
}

// Returns the `$light` color when the `$color` is dark
// and the `$dark` color when the `$color` is light.
// The `$threshold` is a percent between `0%` and `100%` and it determines
// when the lightness of `$color` changes from "dark" to "light".
@function contrast-color(
	$color,
	$dark: black,
	$light: white,
	$threshold: 75%
) {
	@if ( lightness( $color ) < $threshold ) {
		@return $light
	} @else {
		@return $dark;
	}
}

body {
	background: $body-background;
}

body,
button,
input,
select,
textarea {
	color: $text-color;
}

input, textarea {
	color: darken( $alt-color, 10% );

	&:focus {
		outline-color: darken( $body-background, 20% );
	}
}

textarea:focus {
	color: $text-color;
}

button,
input[type="button"],
input[type="reset"],
input[type="submit"] {
	color: contrast-color( $header-color );
	background-color: $header-color;

	&:hover {
		color: contrast-color( darken( $header-color, 10% ) );
		background-color: darken( $header-color, 10% );
	}
}

pre {
	background: $body-background;
}

a {
	color: $link-color;
	@include hoverActiveFocus {
		color: darken($link-color, 30%);
	}
}

.site-content {
	background-color: $main-bg-color;

	$table-color: $sidebar-bg-color;
	th {
		color: lighten( $description-color, 15% );
		background: $table-color;
	}

	td {
		background: mix( white, $table-color, 95% );
	}

	tr:nth-of-type(odd) td {
		background: mix( white, $table-color, 90% );
	}
}

.site-header-bg {
	background-color: $sidebar-bg-color;
}

.site-title a {
	color: $site-title-color;
}

.site-description {
	color: $description-color;
}

.site-footer {
	color: $description-color;
	a {
		color: $description-color;
	}
}

.main-navigation {
	ul {
		li a {
			color: $nav-text-color;
			background-color: $nav-bg-color;

			@include hoverActiveFocus {
				color: $nav-text-color;
				background-color: $nav-current-bg-color;
			}
		}

		.current_page_item > a,
		.current-menu-item > a {
			background-color: $nav-current-bg-color;
		}

		// second level
		ul a {
			background-color: lighten( $nav-bg-color, 4% );
		}
	}
}
.menu-toggle {
	background: $sidebar-bg-color;
}

.taxonomy-description {
	color: $alt-color;
}

.entry-title,
.entry-title a {
	color: $header-color;
}

.entry-meta {
	color: $alt-color;

	a {
		color: $highlight-color;
	}

	.genericon {
		color: $header-color;
		background-color: $header-color;

		&.genericon-comment {
			color: $highlight-color;
			background-color: $highlight-color;
		}
	}

}

.wp-caption {
	background: $body-background;
	color: contrast-color( $body-background );
}

.comment-meta {
	background: $comment-header-bg;

	.avatar {
		border-color: white !important;
	}

	.comment-author {
		&, a, a:hover, a:active, a:focus {
			color: $header-color;
		}
	}

	.comment-metadata {
		&, a, a:hover, a:active, a:focus {
			color: $comment-text;
		}
	}
}
.reply {
	a, a:hover, a:active, a:focus {
		color: $header-color;
	}
}
.comment-awaiting-moderation {
	color: $comment-text;
}

.comment-form {
	input, textarea {
		border-color: $comment-box-border;
	}

	input:focus,
	textarea:focus {
		outline: none;
		border-color: darken( $comment-box-border, 20% );
	}
}

.paging-navigation,
.post-navigation,
.comment-navigation {
	a {
		color: contrast-color( $highlight-color );
		background: $highlight-color;

		&:hover, &:active, &:focus {
			color: contrast-color( $highlight-color );
			background: darken( $highlight-color, 15% );
		}
	}
}

.search-form {
	input {
		background: $body-background;

		@include hoverActiveFocus {
			background: darken( $body-background, 10% );
		}
	}
}

.widget {
	.widget-title a {
		color: $text-color;
	}
}

.widget_calendar {
	th {
		background: $body-background;
	}

	tfoot td {
		background: $highlight-color;

		a {
			color: contrast-color( $highlight-color );
		}
	}
}
SCSS;
// End Scss
