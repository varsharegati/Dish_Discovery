<?php
$sql = "SELECT * FROM category_list where category_id = '{$_GET['cid']}'";
$qry = $conn->query($sql);
foreach($qry->fetchArray() as $k=>$v){
    $$k=$v;
}
?>
<style>
   
</style>
<header id="cover">
    <div class="container-fluid h-100 d-flex flex-column justify-content-end align-items-end">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center w-100">
            <div id="cat-title" class="w-100 text-center wow fadeIn" data-wow-duration="1.2s"><?php echo  $name ?></div>
        </div>
    </div>
</header>
<div class="bg-light">
<div class="my-5 pt-4">
    <div class="container">
        <div class="col-12">
            <div class="row mx-0 d-flex justify-content-cente mb-2">
                <div class="col-12">
                <div class="input-group mb-3">
                    <input type="text" id="search" class="form-control rounded-0" placeholder="Search Here" aria-label="Search Here" aria-describedby="basic-addon2" autocomplete="off">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>
                </div>
                </div>
            </div>
            <div class="row mx-0 row-cols-1 row-cols-sm-1 row-cols-xl-3 gx-5 gy-3" id="recipe_list">
                <?php
                $sql = "SELECT r.*,u.fullname as author FROM recipe_list r inner join user_list u on r.user_id = u.user_id where r.status = 1 and r.category_id = '{$_GET['cid']}' order by strftime('%s',r.date_created) desc";
                $qry = $conn->query($sql);
                $i = 0;
                while($row = $qry->fetchArray()):
                    $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
                ?>
                <div class="item col wow bounceInUp">
                    <div class="card shadow-sm ">
                        <div class="card-body ">
                            <h5 class="card-title mb-1"><?php echo $row['title'] ?></h5>
                            <hr class="bg-primary opacity-100">
                            <p class="truncate-3 fw-light fst-italic lh-1" title="<?php echo $row['description'] ?>"><small><?php echo $row['description'] ?></small></p>
                            <div class="w-100 d-flex justify-content-end">
                                <div class="col-auto flex-grow-1">
                                    <div class="text-muted truncate-1" title="<?php echo $row['author'] ?>"><small><i>Posted by: <?php echo $row['author'] ?></i></small></div>
                                </div>
                                <div class="col-auto">
                                    <a href="./?page=view_recipe&rid=<?php echo $row['recipe_id'] ?>" class="btn btn-sm btn-primary bg-gradient rounded-0 py-0">View Recipes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

            </div>
            <?php if(!$qry->fetchArray()): ?>
                <center><i><small class="text-muted">No data listed yet.</small></i></center>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
<script>
    $(function(){
        $('#search').on('input',function(){
            var _search = $(this).val().toLowerCase()
            $('#recipe_list .item').each(function(){
                var _text = $(this).text().toLowerCase()
                if(_text.includes(_search) == true){
                    $(this).toggle(true)
                }else{
                    $(this).toggle(false)
                }
            })
        })
    })
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
    })
</script>