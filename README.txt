WordPress Last.fm Charts

* Contributors: cheshire137
* Tags: last.fm, charts, chart, graphs, pie, visualization, music, artists, songs, tracks, last, fm, shortcode
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin displays charts of Last.fm data for a particular user.

== Description ==

Using the [Last.fm API](http://www.last.fm/api), this is a WordPress plugin
that provides a shortcode that will display charts
of your Last.fm music-listening data. This plugin uses the [Easy Chart Builder plugin](http://wordpress.org/plugins/easy-chart-builder/) by [dyerware](http://profiles.wordpress.org/dyerware/) to create charts, so
you will need to install and activate that plugin first.

[Download the latest version of the plugin](https://github.com/moneypenny/wp-lastfm-charts/archive/master.zip) or [view the source code](https://github.com/moneypenny/wp-lastfm-charts).

== Installation ==

1. Upload the `wp-lastfm-charts/` folder (that is, the entire plugin folder) to your WordPress `wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in the WordPress admin dashboard.
3. Use the `[wp_lastfm_charts_top_artists]` or `[wp_lastfm_charts_top_tracks]` shortcode in a post or page (see Usage section for details).

== Usage ==

In a post or page, use the `[wp_lastfm_charts_top_artists]` or `[wp_lastfm_charts_top_tracks]` shortcode. Specify the `user_name` parameter, using your Last.fm user name. Specify a time range with the `period` parameter, e.g., `12month`. Specify a chart type with `chart_type`, e.g., `horizbar` or `pie`.

Some examples:

`[wp_lastfm_charts_top_artists user_name="cheshire137" period="12month" chart_type="horizbar"]`

`[wp_lastfm_charts_top_artists user_name="cheshire137" period="12month" chart_type="pie"]`

`[wp_lastfm_charts_top_tracks user_name="cheshire137" period="12month" chart_type="horizbar"]`

`[wp_lastfm_charts_top_tracks user_name="cheshire137" period="12month" chart_type="pie"]`

Other parameters include `chart_color`, `chart_fade_color`, `width`, `height`,
`colors`, `limit`, `title`, `data_table_css`, and `image_alt`.

`chart_color` and `chart_fade_color` refer to the background color of the chart
and should be hex color codes without the #. They default to `FFFFFF` (white).

`limit` refers to the number of artists or tracks to display in the chart. It
defaults to 12.

`colors` should be a comma-separated list of hex color codes without the #.

`period` should be one of `overall`, `7day`, `1month`, `3month`, `6month`, or
`12month`.

== Screenshots ==

1. Top artists from the last twelve months as a horizontal bar chart.
2. Top artists from the last twelve months as a pie chart.
3. Top tracks from the last twelve months as a horizontal bar chart.
4. Top tracks from the last twelve months as a pie chart.
