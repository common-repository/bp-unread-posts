<?php
add_action( 'bp_before_group_forum_content', 'bp_unread_posts' );
add_action( 'bp_directory_forums_extra_cell_head', 'bp_unread_posts_indicator_head', 0);
add_action( 'bp_directory_forums_extra_cell', 'bp_unread_posts_indicator', 0);
add_action( 'bp_after_directory_forums_list', 'bp_unread_posts_button' );

/**
 * update topic status when user reads post
 */
function bp_unread_posts() {
    if (bp_is_group_forum_topic() && is_user_logged_in()) {
        bp_unread_posts_record_activity();
    }
}

/**
 * add header for forum-loop row
 */
function bp_unread_posts_indicator_head() {
    echo("<th id='th-unread'>New</th>");
}

/**
 * add topic status indicator in forum-loop row
 */
function bp_unread_posts_indicator() {
    bp_unread_posts_mark_read();
    echo('<td>'); // class="td-freshness"
    $new_posts = '<img alt="new posts" src="' . WP_PLUGIN_URL . '/bp-unread-posts/images/folder_new.gif">';
    $no_new_posts = '<img alt="no new posts" src="' . WP_PLUGIN_URL . '/bp-unread-posts/images/folder.gif">';
    $new_posts= '<a href="'.bp_unread_posts_topic_last_post_link().'">'.$new_posts.'</a>';
    $no_new_posts= '<a href="'.bp_unread_posts_topic_last_post_link().'">'.$no_new_posts.'</a>';
    echo '<div style="float:left; margin-right:5px">'; //new post indicator
    if ( is_user_logged_in() ) {
        global $bp;
        global $forum_template;
        $this_topic_time = strtotime($forum_template->topic->topic_time);
        $bbuser = $bp->loggedin_user->id;
        $bbtype = 'last_visits_user_';
        $bb_last_visit = bb_get_topicmeta($forum_template->topic->topic_id, $bbtype . $bbuser);
        if ($this_topic_time > $bb_last_visit) {
            echo $new_posts;
        } else {
            echo $no_new_posts;
        }
    } else {
        echo $no_new_posts;
    }
    echo '</div>';
    echo('</td>');
}

/**
 * update topic status for user
 */
function bp_unread_posts_record_activity() {
    global $bp;
    global $forum_template;
    if ( bp_has_forum_topic_posts() ) $bb_this_thread = $forum_template->topic->topic_id;
    if (empty($bb_this_thread)) {
        return $null;
    }
    $bbtype = 'last_visits_user_';
    $bbuser = $bp->loggedin_user->id;
    $bb_this_visit_time = strtotime($forum_template->topic->topic_time);
    $bb_last_visit = bb_get_topicmeta($bb_this_thread, $bbtype . $bbuser);
    if($bb_last_visit != $bb_this_visit_time) {
        bb_update_topicmeta($bb_this_thread, $bbtype . $bbuser, $bb_this_visit_time);
    }
}

/**
 * display button to mark topics read
 */
function bp_unread_posts_button() {
    if ( is_user_logged_in() ) {
        $set_all_read = $_POST['set_all_read'];
        if($set_all_read) {
            echo("<b>Marked all posts as read</b>");
        }else {
            global $forum_template;
            echo("<form method='post'>");
            echo("<input type='hidden' name='set_all_read' value='true'/>");
            if($forum_template->pag_page > 1) {
                echo("<input type='hidden' name='p' value='$forum_template->pag_page'/>");
            }
            echo("<input type='submit' value='mark all read'/>");
            echo("</form>");
        }
    }
}

/**
 * mark the topic as read if button has been pressed
 */
function bp_unread_posts_mark_read() {
    $set_all_read = $_POST['set_all_read'];
    if($set_all_read) {
        global $bp;
        global $forum_template;
        $bbuser = $bp->loggedin_user->id;
        $bbtype = 'last_visits_user_';
        $bb_this_visit_time = strtotime($forum_template->topic->topic_time);
        bb_update_topicmeta($forum_template->topic->topic_id, $bbtype . $bbuser, $bb_this_visit_time);
    }
}

/**
 * get the link to the last unread post
 */
function bp_unread_posts_topic_last_post_link( $per_page = 15 ) {
    global $forum_template;
    $page = bp_unread_posts_get_page_number($forum_template->topic->topic_posts, $per_page);
    $page = (1 < $page) ? '?topic_page='. $page .'&num='. $per_page : '';
    return bp_get_the_topic_permalink() . $page ."#post-". $forum_template->topic->topic_last_post_id;
}

function bp_unread_posts_get_page_number( $item, $per_page = 15 ) {
    if ( !$per_page )
        return false;
    return intval( ceil( $item / $per_page ) );
}

?>