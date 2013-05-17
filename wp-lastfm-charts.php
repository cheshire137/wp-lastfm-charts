<?php
/*
Plugin Name: WordPress Last.fm Charts
Plugin URI:
Description: This plugin displays charts of Last.fm data for a particular user.
Version: 1.0
Author: Sarah Vessels
Author URI: http://www.3till7.net/
License: GPLv3
*/

/*
Copyright Sarah Vessels 2013

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

add_shortcode('wp_lastfm_charts_top_artists',
              'wp_lastfm_charts_top_artists_shortcode');

define('WP_LASTFM_CHARTS_API_KEY', '6c5001ba261721b74bcf01d9a47e2ca2');

function wp_lastfm_charts_top_artists_shortcode($atts) {
  $default_colors = '5E0D5C,C40B49,FF2E0C,FF990A,90B207,0ABD9C,38C1F5,004DD0,' .
                    'AF33C8,FF7EAB,B77087,828282';
  extract(shortcode_atts(array(
    'user_name' => 'rj',
    'period' => 'overall',
    'chart_color' => 'FFFFFF',
    'chart_fade_color' => 'FFFFFF',
    'width' => '200',
    'height' => '200',
    'colors' => $default_colors,
    'limit' => 12,
    'title' => 'Top Artists',
    'data_table_css' => '',
    'image_alt' => 'Top Artists chart'
  ), $atts));
  $valid_periods = array('overall', '7day', '1month', '3month', '6month',
                         '12month');
  if (!in_array($period, $valid_periods)) {
    $period = 'overall';
  }
  // Easy Chart Builder allows up to 12 groups in a chart
  $limit = ('' == $limit || $limit > 12) ? 12 : $limit;
  $url = 'http://ws.audioscrobbler.com/2.0/?method=user.gettopartists&user='.
         $user_name.'&api_key='.WP_LASTFM_CHARTS_API_KEY.'&format=json'.
         '&period='.$period.'&limit='.$limit;
  $ch = curl_init($url);
  $options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json')
  );
  curl_setopt_array($ch, $options);
  $json_result = curl_exec($ch);
  $result = json_decode($json_result);
  $group_names = array();
  $group_values = array();
  $artists = $result->{'topartists'}->{'artist'};
  foreach ($artists as $index => $artist) {
    $group_names[] = str_replace(',', ';', $artist->{'name'});
    $group_values[] = 'group'.($index + 1).'values="'.$artist->{'playcount'}.
                      '"';
  }
  $group_names = implode(',', $group_names);
  $group_values = implode(' ', $group_values);
  return do_shortcode('[easychart type="pie" groupnames="'.$group_names.'" '.
                      $group_values.' chartfadecolor="'.$chart_fade_color.
                      '" chartcolor="'.$chart_color.'" width="'.$width.
                      '" height="'.$height.'" groupcolors="'.$colors.
                      '" title="'.$title.'" datatablecss="'.$data_table_css.
                      '" imagealtattr="'.$image_alt.'"]');
}
?>
