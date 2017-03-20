
        <h2><strong>Single Post</strong></h2>
        
        <h3>Post ID: <?=$id?></h3>
        
        <div>
            Original view of this page is 'blog/post/index/<?=$id?>'. But a routing rule is being used to route:<br />
            blog/post/<?=$id?> => blog/post/index/<?=$id?>
        </div>
        
        <h3>Session</h3>
        <?php print_r($session) ?>
        
        <h3>Users</h3>
        <?php print_r($users) ?>
        
        <h3>Posts</h3>
        <?php print_r($posts) ?>
        
        <h3>Comments</h3>
        <?php print_r($comments) ?>
        
        <div class="note">This view is located at: application/modules/blog/views/single_post.php</div>
