<div class="container text-center">
    <h1>Page not found!</h1>
    <p>An error occurred, that page not found!</p>
    <?php
    if(isset($this->attr['message'])) {
        echo '<p>' . $this->attr['message'] . '</p>';
    }
    ?>
</div>