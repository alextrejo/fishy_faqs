<?php
if(sizeof($data['items'])){
?>
<div id="accordion" class="faq-accordion">
<?php
  foreach($data['items'] as $item){
?>
  <h3><?php echo $item['title'];?></h3>
  <div>
    <?php echo apply_filters('the_content', $item['content']);?>
  </div>
<?php
  }
?>
</div>
<?php
}
?>

<script type="text/javascript">
(function($){
    $('document').ready(function(){
      $('#accordion').accordion({
        active: false,
        collapsible: true,
        heightStyle: "content"
      });
    });
})(jQuery);
</script>