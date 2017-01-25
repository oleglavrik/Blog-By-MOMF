<div class="container text-center">
    <h1>An error!</h1>
    <p>An error occurred.</p>
    <?php
        if(isset($this->attr['message'])) {
            echo '<p>' . $this->attr['message'] . '</p>';
        }
    ?>
</div>