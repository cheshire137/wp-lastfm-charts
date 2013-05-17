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
add_shortcode('wp_lastfm_charts_top_tracks',
              'wp_lastfm_charts_top_tracks_shortcode');

define('WP_LASTFM_CHARTS_API_KEY', '6c5001ba261721b74bcf01d9a47e2ca2');

function wp_lastfm_charts_top_artists_shortcode($atts) {
  extract(shortcode_atts(array(
    'user_name' => 'rj',
    'period' => 'overall',
    'chart_color' => 'FFFFFF',
    'chart_fade_color' => 'FFFFFF',
    'width' => '200',
    'height' => '200',
    'colors' => wp_lastfm_charts_colors(),
    'limit' => 12,
    'title' => null,
    'data_table_css' => '',
    'image_alt' => 'Top Artists chart',
    'chart_type' => 'pie'
  ), $atts));
  $period = wp_lastfm_charts_period($period);
  $chart_type = wp_lastfm_charts_chart_type($chart_type);
  $title = wp_lastfm_charts_top_artists_title($title, $period, $user_name);
  $limit = wp_lastfm_charts_limit($limit);
  $url = 'http://ws.audioscrobbler.com/2.0/?method=user.gettopartists&user='.
         $user_name.'&api_key='.WP_LASTFM_CHARTS_API_KEY.'&format=json'.
         '&period='.$period.'&limit='.$limit;
  $result = wp_lastfm_charts_get_result($url);
  $names = array();
  $values = array();
  foreach ($result->{'topartists'}->{'artist'} as $artist) {
    $group_name = str_replace(',', ';', $artist->{'name'});
    $group_name = str_replace('"', "'", $group_name);
    $names[] = $group_name;
    $values[] = $artist->{'playcount'};
  }
  return wp_lastfm_charts_easychart(array(
    'chart_type' => $chart_type,
    'names' => $names,
    'values' => $values,
    'chart_fade_color' => $chart_fade_color,
    'chart_color' => $chart_color,
    'width' => $width,
    'height' => $height,
    'colors' => $colors,
    'title' => $title,
    'data_table_css' => $data_table_css,
    'image_alt' => $image_alt,
    'group_name' => 'Artist'
  ));
}

function wp_lastfm_charts_top_tracks_shortcode($atts) {
  extract(shortcode_atts(array(
    'user_name' => 'rj',
    'period' => 'overall',
    'chart_color' => 'FFFFFF',
    'chart_fade_color' => 'FFFFFF',
    'width' => '200',
    'height' => '200',
    'colors' => wp_lastfm_charts_colors(),
    'limit' => 12,
    'title' => null,
    'data_table_css' => '',
    'image_alt' => 'Top Tracks chart',
    'chart_type' => 'pie'
  ), $atts));
  $period = wp_lastfm_charts_period($period);
  $chart_type = wp_lastfm_charts_chart_type($chart_type);
  $title = wp_lastfm_charts_top_tracks_title($title, $period, $user_name);
  $limit = wp_lastfm_charts_limit($limit);
  $url = 'http://ws.audioscrobbler.com/2.0/?method=user.gettoptracks&user='.
         $user_name.'&api_key='.WP_LASTFM_CHARTS_API_KEY.'&format=json'.
         '&period='.$period.'&limit='.$limit;
  $result = wp_lastfm_charts_get_result($url);
  $names = array();
  $values = array();
  foreach ($result->{'toptracks'}->{'track'} as $track) {
    $track_name = $track->{'name'};
    $artist_name = $track->{'artist'}->{'name'};
    $name = str_replace(',', ';', $track_name.' - '.$artist_name);
    $name = str_replace('"', "'", $name);
    $names[] = $name;
    $values[] = $track->{'playcount'};
  }
  return wp_lastfm_charts_easychart(array(
    'chart_type' => $chart_type,
    'names' => $names,
    'values' => $values,
    'chart_fade_color' => $chart_fade_color,
    'chart_color' => $chart_color,
    'width' => $width,
    'height' => $height,
    'colors' => $colors,
    'title' => $title,
    'data_table_css' => $data_table_css,
    'image_alt' => $image_alt,
    'group_name' => 'Track'
  ));
}

function wp_lastfm_charts_easychart($attribs) {
  extract($attribs);
  $shortcode = '[easychart type="'.$chart_type.'" chartfadecolor="'.
               $chart_fade_color.'" chartcolor="'.$chart_color.
               '" width="'.$width.'" height="'.$height.'" groupcolors="'.
               $colors.'" title="'.$title.'" datatablecss="'.
               $data_table_css.'" imagealtattr="'.$image_alt.'"';
  switch ($chart_type) {
    case 'horizbar':
      $names = implode(',', $names);
      $values = implode(',', $values);
      $shortcode .= ' valuenames="'.$names.'" group1values="'.
                    $values.'" groupnames="'.$group_name.'"';
      break;
    case 'pie':
      foreach ($values as $index => $value) {
        $values[$index] = 'group'.($index + 1).'values="'.$value.'"';
      }
      $names = implode(',', $names);
      $values = implode(' ', $values);
      $shortcode .= ' groupnames="'.$names.'" '.$values;
      break;
  }
  $shortcode .= ']';
  return do_shortcode($shortcode);
}

function wp_lastfm_charts_top_artists_title($title, $period, $user_name) {
  return wp_lastfm_charts_title('Top Artists', $title, $period, $user_name);
}

function wp_lastfm_charts_top_tracks_title($title, $period, $user_name) {
  return wp_lastfm_charts_title('Top Tracks', $title, $period, $user_name);
}

function wp_lastfm_charts_chart_type($chart_type) {
  $valid_chart_types = array('horizbar', 'pie');
  if (!in_array($chart_type, $valid_chart_types)) {
    $chart_type = 'pie';
  }
  return $chart_type;
}

function wp_lastfm_charts_period($period) {
  $valid_periods = array('overall', '7day', '1month', '3month', '6month',
                         '12month');
  if (!in_array($period, $valid_periods)) {
    $period = 'overall';
  }
  return $period;
}

function wp_lastfm_charts_title($prefix, $title, $period, $user_name) {
  if (null == $title) {
    $title = $prefix.' for '.$user_name.' - ';
    switch($period) {
      case 'overall': $title.='Overall'; break;
      case '7day': $title.='Last Week'; break;
      case '1month': $title.='Last Month'; break;
      case '3month': $title.='Last 3 Months'; break;
      case '6month': $title.='Last 6 Months'; break;
      case '12month': $title.='Last 12 Months'; break;
    }
  }
  return $title;
}

function wp_lastfm_charts_limit($limit) {
  // Easy Chart Builder allows up to 12 groups in a chart
  return ('' == $limit || $limit > 12) ? 12 : $limit;
}

function wp_lastfm_charts_colors() {
  return 'b84832,ff9429,ebb736,64ad50,23b07f,1c8f9c,2457b5,4c2cc9,6d23cf,'.
         'b92bcf,ed61cf,c41247';
}

function wp_lastfm_charts_get_result($url) {
  $ch = curl_init($url);
  $options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json')
  );
  curl_setopt_array($ch, $options);
  $json_result = curl_exec($ch);
  return json_decode($json_result);
}
?>
