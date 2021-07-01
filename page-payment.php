<?php 

get_header();
?>

<form id="frmContactus" action="" method="post">
  <label for="name">Name</label><br>
  <input type="text" id="name" name="name" required="required"><br>
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" required="required">
  <input type="submit" id="submit" name="submit" value="submit">

</form>
<script type="text/javascript">
  jQuery('#frmContactus').submit(function(evt){
    evt.preventDefault();
    var link="<?php echo admin_url('admin-ajax.php'); ?>";
    var form=jQuery('#frmContactus').serialize();
    var formData=new FormData();
    formData.append('action', 'payment');
    formData.append('payment', form);
    //alert(link);
    jQuery.ajax({
      processData:false,
      contentType:false,
      cache:false,
      url:link,
      method: "post",
      data: formData,
      success:function(result) {
            alert(result);
        }
    });
  });



</script>