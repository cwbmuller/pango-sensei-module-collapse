<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * List the Course Modules and Lesson in these modules
 *
 * Template is hooked into Single Course sensei_single_main_content. It will
 * only be shown if the course contains modules.
 *
 * All lessons shown here will not be included in the list of other lessons.
 *
 * @author 		Automattic
 * @package 	Sensei
 * @category    Templates
 * @version     1.9.0
 */
?>

<?php

/**
 * Hook runs inside single-course/course-modules.php
 *
 * It runs before the modules are shown. This hook fires on the single course page. It will show
 * irrespective of irrespective the course has any modules or not.
 *
 * @since 1.8.0
 *
 */
do_action('sensei_single_course_modules_before');

?>

<?php if( sensei_have_modules() ): ?>

    <?php while ( sensei_have_modules() ): sensei_setup_module(); ?>
        <?php if( sensei_module_has_lessons() ): ?>

            <article class="module">

                <?php

                /**
                 * Hook runs inside single-course/course-modules.php
                 *
                 * It runs inside the if statement after the article tag opens just before the modules are shown. This hook will NOT fire if there
                 * are no modules to show.
                 *
                 * @since 1.9.0
                 *
                 * @hooked Sensei()->modules->course_modules_title - 20
                 */
                do_action('sensei_single_course_modules_inside_before');

                // Collect the Module Collapse setting options
                $title_setting = $this->get_setting('sensei_module_title');
                $media_setting = $this->get_setting('sensei_module_notes');
                $video_setting = $this->get_setting('sensei_module_video');
                $time_setting = $this->get_setting('sensei_module_lesson_time');
                $quiz_setting = $this->get_setting('sensei_module_quiz');
                ?>



                <section class="entry">



                    <section class="module-lessons">



                        <header class="expList">

                            <h2 class="expList">
                                <span class="expList module-title">
                                <?php

                                // Condition to display Module title as link or plain text
                                if (!$title_setting) { ?>
                                <a href="<?php sensei_the_module_permalink(); ?>" title="<?php sensei_the_module_title_attribute();?>">

                                    <?php sensei_the_module_title(); ?>

                                </a>
                                <?php
                                }
                                else {
                                    sensei_the_module_title();
                                }
                                ?>
                                </span>
                                <i class='expList fa tog-mod fa-chevron-down'></i>
                                <?php sensei_the_module_status(); ?>
                            </h2>

                        </header>

                        <ul class="lessons-list expList2" >

                            <?php while( sensei_module_has_lessons() ): the_post();

                                //$status = '';
                                $lessons_time = '';

                                // Collect various meta data from lessons
                                //$lesson_completed = WooThemes_Sensei_Utils::user_completed_lesson(get_the_ID(), get_current_user_id());
                                $lesson_video = get_post_meta( get_the_ID(), '_lesson_video_embed', true );
                                $lesson_length = get_post_meta(get_the_ID(), '_lesson_length', true);
                                $lesson_media = get_post_meta(get_the_ID(), '_attached_media', true );
                                $lesson_quiz = get_post_meta( get_the_ID(), '_quiz_has_questions', true );

                                // Get lesson completed status
                                //if ($lesson_completed) {
                                //    $status = 'completed';
                                //}

                                // Check if lesson has a video
                                $has_video = '';
                                if ('' != $lesson_video && ($video_setting)) {
                                    $has_video = '<i class="fa fa-video-camera"></i> ';
                                }

                                // Check if lesson has a media
                                $has_media = '';
                                if ( isset( $lesson_media ) && is_array( $lesson_media ) && count( $lesson_media ) > 0  && ($media_setting)) {
                                    $has_media = '<i class="fa fa-file"></i> ';
                                }

                                // Check if lesson has a quiz
                                $has_quiz = '';
                                if ( $lesson_quiz &&  ($quiz_setting)) {
                                    $has_quiz = '<i class="fa fa-check-square-o"></i> ';
                                }

                                // Get lesson time and set variable if it exists
                                if (('' != $lesson_length) && ($time_setting) ){
                                    $lessons_time = '<i class="fa fa-clock-o"></i> '.$lesson_length.__('m', 'woothemes-sensei').'';
                                }
                                ?>



                                <li class="<?php sensei_the_lesson_status_class();?>">

                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" >
                                        <?php $course_id = Sensei()->lesson->get_course_id( get_the_ID() ); ?><p><span class="lesson-title">
                                        <?php the_title(); ?></span><span class="lesson-length"><?php echo $has_quiz . $has_media . $has_video.$lessons_time; ?></span>
                                        <?php

                                        if ( Sensei_Utils::is_preview_lesson( get_the_ID() ) && ! Sensei_Utils::user_started_course( $course_id, get_current_user_id() )  ) { ?>

                                            <span class="preview-label">Free Preview</span>

                                        <?php } ?></p>
                                    </a>

                                </li>

                            <?php endwhile; ?>

                        </ul>

                    </section><!-- .module-lessons -->

                </section>

                <?php

                /**
                 * Hook runs inside single-course/course-modules.php
                 *
                 * It runs inside the if statement before the closing article tag directly after the modules were shown.
                 * This hook will not trigger if there are no modules to show.
                 *
                 * @since 1.9.0
                 *
                 */
                do_action('sensei_single_course_modules_inside_after');

                ?>

            </article>

        <?php endif; //sensei_module_has_lessons  ?>

    <?php endwhile; // sensei_have_modules ?>

<?php endif; // sensei_have_modules ?>

<?php

/**
 * Hook runs inside single-course/course-modules.php
 *
 * It runs after the modules are shown. This hook fires on the single course page,but only if the course has modules.
 *
 * @since 1.8.0
 */
do_action('sensei_single_course_modules_after');