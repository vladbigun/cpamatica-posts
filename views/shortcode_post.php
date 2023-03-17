<div class="cpamatica_posts_container">
    <?php if(isset($attr['title'])):?>
        <h2><?= $attr['title'] ?></h2>
    <?php endif;?>

    <?php if ( $query->have_posts() ) : ?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="cpamatica_post_wrapper">
                <div class="cpamatica_post_image">
                    <?= get_the_post_thumbnail( get_the_ID(), 'medium' );  ?>
                </div>
                <div class="cpamatica_post_content">
                    <div class="cpamatica_post_top">
                        <span><?= the_category(); ?></span>
                        <h3><?php the_title(); ?></h3>
                    </div>
                    <div class="cpamatica_post_bottom">
                        <a class="cpamatica_post_read_more" href="<?= get_permalink() ?>"><?= __('Read More') ?></a>
                        <div class="cpamatica_post_bottom_right">
                            <?php
                            $rating = get_post_meta(get_the_ID(), 'rating', true);
                            if($rating):?>
                                <span class="cpamatica_post_rating">⭐ <?= $rating ?></span>
                            <?php endif;?>
                            <?php
                            $link = get_post_meta(get_the_ID(), 'link', true);
                            if($link):?>
                                <a rel="nofollow" target="_blank" href="<?= $link ?>" class="cpamatica_post_button"><?= __('Visit Site') ?></a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif;?>

</div>