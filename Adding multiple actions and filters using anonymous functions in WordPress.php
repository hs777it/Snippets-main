<?php

// Adding multiple actions and filters using anonymous functions in WordPress

add_filter('the_content', 'filter_the_content');
function filter_the_content($content)
{
    //Do something with the content
    return $content;
}
//
add_filter('the_content', function($content)
{
    //Do something with the content
    return $content;
});


//////////////////////////////////////////////////////////////////////
//Both the_content and widget_text will be filtered through filter_the_content()
add_filter('the_content', 'filter_the_content');
add_filter('widget_text', 'filter_the_content');
function filter_the_content($content)
{
    //Do something with the content
    return $content;
}


//////////////////////////////////////////////////////////////////////
//Filter the_content
add_filter('the_content', function($content)
{
    //Do something with the content
    return $content;
});
//Filter widget_text
add_filter('widget_text', function($content)
{
    //Do something with the content
    return $content;
});

//////////////////////////////////////////////////////////////////////
add_filters(array( 'the_content', 'widget_text' ), function($content)
{
  //Do something with both the_content and widget_text filters
  return $content;
});

add_filters(array( 'the_content', 'widget_text' ), function($content)
{
  //Do something with both the_content and widget_text filters
  return $content;
},
array(15,20)); //the_content filter will have priority 15 and widget_text filter will have priority 20

//////////////////////////////////////////////////////////////////////




//////////////////////////////////////////////////////////////////////