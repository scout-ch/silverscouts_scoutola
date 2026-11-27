<?php

function silverscouts_plugin_hook_home_search_pre()
{
  ?>
    <section class="home-search">
      <div class="wrapper">
        XXX
      </div>
    </section>
  <?php
}
osc_add_hook('home_search_pre', 'silverscouts_plugin_hook_home_search_pre');

function silverscouts_plugin_hook_header()
{
  osc_enqueue_style('silverscouts', osc_current_web_theme_url('css/silverscouts.css'));
  osc_enqueue_style('font-sigma', 'https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap');
  osc_remove_style('font-nunito');

}
osc_add_hook('header', 'silverscouts_plugin_hook_header');
