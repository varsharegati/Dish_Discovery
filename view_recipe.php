<?php
$sql = "SELECT r.*,u.fullname as author FROM recipe_list r inner join user_list u on r.user_id = u.user_id where r.recipe_id = '{$_GET['rid']}'";
$qry = $conn->query($sql);
foreach($qry->fetchArray() as $k=>$v){
    $$k=$v;
}
?>
<header id="cover">
    <div class="container-fluid h-100 d-flex flex-column justify-content-end align-items-end">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center w-100">
            <div id="cat-title" class="w-100 text-center wow fadeIn" data-wow-duration="1.2s"><?php echo  $title ?></div>
        </div>
    </div>
</header>
<div class="bg-light">
    <div class="container py-5 mt-4">
        <h2 class="text-center wow fadeIn"><?php echo $title ?>'s Recipe Details</h2>
        <hr class="m-0">
        <div class="w-100 d-flex justify-content-between mb-2">
            <div class="col-auto  wow slideInLeft">
                <span class="text-muted"><small><i class="fa fa-user"></i>Posted by: <?php echo $author ?></small></span>
            </div>
            <div class="col-auto wow slideInRight">
                <span class="text-muted"><small><i class="fa fa-calendar-day"></i> Published: <?php echo date("M d, Y H:i",strtotime($date_created)) ?></small></span>
            </div>
        </div>
        <?php if(is_file('./uploads/'.$recipe_id.'.png')): ?>
            <center class="wow bounceInUp">
                <img src="./uploads/<?php echo $recipe_id ?>.png" alt="<?php echo  $title ?>" class="bg-gradient img-fluid recipe-img">
            </center>
        <?php endif; ?>
        <div class="content">
            <div class="my-2">
                <?php echo html_entity_decode($description) ?>
            </div>
            <h2 class="strikeBg text-center">Ingredients</h2>
            <div class="my-2">
                <?php echo html_entity_decode($ingredients) ?>
            </div>
            <h2 class="strikeBg text-center">Cooking Steps</h2>
            <div class="my-2">
                <?php echo html_entity_decode($step) ?>
            </div>
            <h2 class="strikeBg text-center">Other Information</h2>
            <div class="my-2">
                <?php echo html_entity_decode($other_info) ?>
            </div>
        </div>
        <hr>
        <div class="col-md-7">
            <h2 class="wow fadeIn"><b>Comment/s:</b></h2>
            <hr>
            <?php 
            $comments = $conn->query("SELECT c.*,u.fullname FROM `comment_list` c inner join user_list u on c.user_id = u.user_id where c.recipe_id = '{$recipe_id}' order by strftime('%s',c.date_created) asc");
            while($row = $comments->fetchArray()):
            ?>
            <div class="w-100 bg-white px-1 py-1 mb-2 shadow wow bounceInUp">
                <div class="w-100 d-flex align-items-center">
                    <span class="user-avatar rounded-circle border border-dark bg-light bg-gradient me-2"><i class="fa fa-user"></i></span>
                    <span class="lh-2 flex-grow-1 ">
                    <span class="text-muted truncate-1 w-100 mb-1" title="<?php echo $row['fullname'] ?>"><?php echo $row['fullname'] ?></span>
                    <span><small class="text-muted comment-date"><i><?php echo date("M d,Y H:i",strtotime($row['date_created'])) ?></i></small></span>
                    </span>
                    <?php if((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) || (isset($_SESSION['admin_id']))): ?>
                        <button class="btn btn-sm btn-primary rounded-circle edit_comment" data-id="<?php echo $row['comment_id'] ?>"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger rounded-circle delete_comment" data-id="<?php echo $row['comment_id'] ?>"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                </div>
                <hr>
                <p class="ms-2 comment-msg" data-id="<?php echo $row['comment_id'] ?>"><?php echo $row['message'] ?></p>
            </div>
            <?php endwhile; ?>
            <hr>
            <form action="" id="comment-form" class="wow bounceInUp">
                <input type="hidden" name="id">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe_id ?>">
                <?php 
                    if(isset($_SESSION['comment_flashdata']['type']) && isset($_SESSION['comment_flashdata']['msg'])):
                ?>
                <div class="dynamic_alert form-group alert alert-<?php echo $_SESSION['comment_flashdata']['type'] ?>">
                <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
                <?php echo $_SESSION['comment_flashdata']['msg'] ?></div>
                <?php 
                    unset($_SESSION['comment_flashdata']);
                    endif;
                ?>
                <div class="form-group">
                    <textarea name="message" id="message" cols="30" rows="4" class="form-control rounded-0" placeholder="Write your comment here."></textarea>
                </div>
                <div class="form-group">
                    <div class="w-100 d-flex justify-content-end my-2">
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm rounded-0">Save</button>
                            <button class="btn btn-dark btn-sm rounded-0" type="reset">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function(){
    var content = $('.content')
    var cloned = content.clone()
    var el = $('<div class="content-show">')
    cloned.find('p,h1,h2,h3,h4,h5,ul,ol').each(function(){
        $(this).addClass("wow fadeIn")
    })
    el.append(cloned.html())
    content.replaceWith(el)

    $('.delete_comment').click(function(){
        _conf("Are you sure to delete comment?",'delete_comment',[$(this).attr('data-id')])
    })

    $('.edit_comment').click(function(){
        var id = $(this).attr('data-id')
        var msg = $('.comment-msg[data-id="'+id+'"]').text()
        $('[name="id"]').val(id)
        $('[name="message"]').val(msg)
        $('html,body').animate({scrollTop:$('[name="message"]').offset().top},'fast')
        $('[name="message"]').focus()
    })

})
function delete_comment($id){
    $('#confirm_modal button').attr('disabled',true)
    $.ajax({
        url:'./Actions.php?a=delete_comment',
        method:'POST',
        data:{id:$id},
        dataType:'JSON',
        error:err=>{
            console.log(err)
            alert("An error occurred.")
            $('#confirm_modal button').attr('disabled',false)
        },
        success:function(resp){
            if(resp.status == 'success'){
                location.reload()
            }else{
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            }
        }
    })
}
</script>

<script>
    $(document).scroll(function() { 
        $('#topNavBar').removeClass('bg-transaparent bg-dark')
        if($(window).scrollTop() === 0) {
           $('#topNavBar').addClass('bg-transaparent')
        }else{
           $('#topNavBar').addClass('bg-dark')
        }
    });
    $(function(){
        $(document).trigger('scroll')
        $('#comment-form').on('reset',function(){
        $('[name="id"]').val('')
        })
        $('#comment-form').submit(function(e){
            e.preventDefault();
            if('<?php echo isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) ?>' != 1){
                location.replace('./?page=login_registration')
                return false;
            }
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./Actions.php?a=save_comment',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload()
                        _el.addClass('alert alert-success')
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('html,body').animate({scrollTop:$('#comment-form').offset().top },'fast')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>