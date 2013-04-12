
        <h2><strong>Single Post</strong></h2>
        
        <h3>Post ID: <?php echo $this->id ?></h3>
        
        <div>
            Original view of this page is 'blog/post/index/<?php echo $this->id ?>'. But a routing rule is being used to route:<br />
            blog/post/<?php echo $this->id ?> => blog/post/index/<?php echo $this->id ?>
        </div>
        
        <h3>Session</h3>
        <?php print_r($this->session) ?>
        
        <h3>Users</h3>
        <?php print_r($this->users) ?>
        
        <h3>Posts</h3>
        <?php print_r($this->posts) ?>
        
        <h3>Comments</h3>
        <?php print_r($this->comments) ?>
        
        <div class="note">This view is located at: application/modules/blog/views/single_post.php</div>
