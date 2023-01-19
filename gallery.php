<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
require_once(__DIR__ . '/base.php');

class Elementor_Gallery extends Redboa_Base
{
    public function get_name()
    {
        return 'theme_gallery';
    }

    public function get_title()
    {
        return esc_html__('گالری تصاویر', REDBOA_SLUG);
    }

    public function get_icon()
    {
        return 'eicon-code';
    }

    public function get_keywords()
    {
        return ['gallery', 'image', 'عکس', 'گالری'];
    }

    protected function register_controls()
    {

        // Content Tab Start

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('عمومی', REDBOA_SLUG),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $categories = get_terms(array(
            'taxonomy' => 'redboa_gallery',
            'hide_empty' => true,
        ));

        $tagOptions = (array) null;
        foreach ($categories as $key => $value) {
            $term = new WP_Term($value);
            $id = $term->term_id;
            $name = esc_html__($term->name);
            $tagOptions[$id] = $name;
        }


        $this->add_control(
            'tags',
            [
                'label' => 'تگ ها',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $tagOptions,
                'default' => [],
            ]
        );

        $this->add_control(
            'sub_title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__('زیرعنوان', REDBOA_SLUG),
                'placeholder' => '',
            ]
        );
        $this->add_control(
            'title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__('عنوان', REDBOA_SLUG),
                'placeholder' => '',
            ]
        );

        $this->add_control(
            'filter_all',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('نمایش فیلتر «همه»', REDBOA_SLUG),
                'options' => [
                    'default' => esc_html__('Default', REDBOA_SLUG),
                    'yes' => esc_html__('بله', REDBOA_SLUG),
                    'no' => esc_html__('خیر', REDBOA_SLUG),
                ],
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Content Tab End
    }

    // Retrieve icon control type.

    /*
    protected function content_template()
    {
        $tagIds = $settings[self::elementor_prefix . 'tags'];
        $tags = get_terms(array(
            'taxonomy' => 'redboa_gallery',
            'hide_empty' => true,
            'ids' => $tagIds
        ));

        ?>
        
        <!-- Gallery -->
        <section class="portfolio pt-120 pb-120 pos-re">
            <div class="container">
                <div class="row">
                    <div class="col-md-12  mb-40 text-center">

                        <# if(settings.gallery_section_sub_title != ''){ #>
                        <h6 class="sub-title">{{{settings.gallery_section_sub_title}}}</h6>
                        <# } #>
                        
                        <# if(settings.gallery_section_title != ''){ #>
                        <h4 class="title">{{{settings.gallery_section_title}}}</h4>
                        <# } #>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12  text-center">
                        <div class="row">
                            <!-- filter links -->
                            <div class="filtering text-center mb-20 col-sm-12">

                                <# if(settings.gallery_filter_all == 'yes') { 
                                    console.log(settings.tagOptions); #>
                                    <span data-filter='*' class="active">همه</span>
                                <# } #>

                                <# 
                                console.log(settings.gallery_tags);
                                _.each( settings.gallery_tags, function( item ) { #>
                                <span>{{{item.name}}}</span>
                                <# }); #>


                            </div>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
*/
    #region private codes

    function displaySectionSubTitle($text)
    {
        if (!IsNullOrEmptyString($text)) {
        ?>
            <h6 class="sub-title"><?php echo $text; ?></h6>
        <?php
        }
    }

    function displaySectionTitle($text)
    {
        if (!IsNullOrEmptyString($text)) {
        ?>
            <h4 class="title"><?php echo $text; ?></h4>
        <?php
        }
    }


    #endregion

    protected function render()
    {
        $categories = get_terms(array(
            'taxonomy' => 'redboa_gallery',
            'hide_empty' => true,
        ));

        $tagOptions = (array) null;
        foreach ($categories as $key => $value) {

            $term = new WP_Term($value);
            $id = $term->term_id;
            $name = esc_html__($term->name);
            $tagOptions[$id] = $name;
        }

        $settings = $this->get_settings_for_display();
        $prefix = generateRandomString() . '_';


        $filterall = $settings['filter_all'];
        $section_sub_title = $settings['sub_title'];
        $section_title = $settings['title'];

        $tagIds = $settings['tags'];

        $tags = get_terms(array(
            'taxonomy' => 'redboa_gallery',
            'hide_empty' => true,
            'include' => $tagIds,
        ));

        ?>

        <!-- Gallery -->
        <section class="portfolio pt-120 pb-120 pos-re">
            <div class="container">
                <div class="row">
                    <div class="col-md-12  mb-40 text-center">
                        <?php self::displaySectionSubTitle($section_sub_title); ?>
                        <?php self::displaySectionTitle($section_title); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12  text-center">
                        <div class="row">
                            <!-- filter links -->
                            <div class="filtering text-center mb-20 col-sm-12">

                                <?php if ($filterall == 'yes') { ?>
                                    <span data-filter='*' class="active">همه</span>
                                <?php } ?>
                                <?php
                                $length = count($tags);
                                for ($i = 0; $i < $length; $i++) {
                                    $item = $tags[$i];
                                ?>

                                    <span data-filter='.<?php echo $prefix . $item->term_id ?>'><?php echo $item->name ?></span>

                                <?php  } ?>
                            </div>
                            <div class="clearfix"></div>

                            <!-- gallery -->
                            <div class="gallery text-center full-width">

                                <?php
                                $args = array(
                                    'post_type' => 'redboa_gallery',
                                    'tax_query' => array(
                                        'taxonomy' => 'redboa_gallery',
                                        'field'    => 'term_id',
                                        'terms'    => $tagIds
                                    )
                                );

                                $my_query = new WP_Query($args);

                                if ($my_query->have_posts()) :
                                    while ($my_query->have_posts()) : $my_query->the_post();

                                        $postTerms = wp_get_post_terms($my_query->post->ID, 'redboa_gallery');
                                        $class = '';
                                        foreach ($postTerms as $key => $value) {
                                            $class .= $prefix . $value->term_id . ' ';
                                        }

                                ?>


                                        <div class="col-md-4 items <?php echo $class ?>">
                                            <a href="<?php echo get_the_post_thumbnail_url($my_query->post, 'full'); ?>" class="popimg">
                                                <div class="item-img">
                                                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo get_the_title(); ?>">
                                                    <div class="item-img-overlay valign">
                                                        <div class="overlay-info full-width vertical-center">
                                                            <h6><?php echo get_the_title(); ?></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                <?php

                                    endwhile;
                                endif;
                                wp_reset_query();
                                ?>

                                <div class="clear-fix"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php



    }
}
