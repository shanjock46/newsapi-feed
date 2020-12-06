<?php


class newsApiPostFeed extends WP_Widget {
	public function __construct() {
		// ID of the element that wraps the widget
		$widget_id = 'widget_newsapi_post_feed';

		// Widget name in the admin panel
		$widget_name = 'News Api Post Feed';

		// Additional wrapper attributes
		$widget_options = [
			'classname'   => 'widget-newsapi-post-feed',
			'description' => 'News Api post feed based on custom filter'
		];

		// Parent constructor
		parent::__construct( $widget_id, $widget_name, $widget_options );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		// Turns the $args array into separate variables ($before_widget, etc)
		extract( $args, EXTR_SKIP );
		define( 'NEWSAPI_FEED_TEXT_DOMAIN', 'Newsapi Post Feed Widget' );
		$defaults = [
			'title'          => 'News API Post Feed',
			'newsApiKeyword' => '',
			'newsApiKey'     => '',
			'total'          => '5',
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$instance['title'] = __( $instance['title'], NEWSAPI_FEED_TEXT_DOMAIN );

		// Used by themes
		echo $before_widget;

		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );

		// Generate the html tag to display the title
		$titleTag = '';
		if ( ! empty( trim( $title ) ) ) {
			$titleTag = $before_title . '<span>' . $title . '</span>' . $after_title;
		}
		echo '<div class="widget-newsapi-post-feed-container">';
		echo '<h3 class="side-title">' . $title . '</h3>';

		if ( isset( $instance['newsApiKeyword'] ) && strlen( $instance['newsApiKeyword'] ) > 0 && isset( $instance['newsApiKey'] ) && strlen( $instance['newsApiKey'] ) > 0 ) {

			// create curl resource
			$ch = curl_init();

			// set url
			curl_setopt( $ch, CURLOPT_URL, "https://newsapi.org/v2/everything?q=" . $instance['newsApiKeyword'] . "&apiKey=" . $instance['newsApiKey'] );

			//return the transfer as a string
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			// $output contains the output string
			$output = curl_exec( $ch );

			// close curl resource to free up system resources
			curl_close( $ch );

			$response = json_decode( $output );
			if ( $response->status === 'ok' ) {
				if ( $response->totalResults > 0 ) {
					$lists = $response->articles;

					echo '<ul class="list-unstyled wrap">';
					for ( $i = 0; $i < intval( $instance['total'] ); $i ++ ) {
						echo '<li><a target="_blank" href="' . $lists[ $i ]->url . '" title="' . $lists[ $i ]->title . '">' . $lists[ $i ]->title . '</a></li>';
					}
					echo '</ul>';

				} else {
					echo "No record found";
				}
			} else {
				echo $response->message;
			}

		} else {
			if ( strlen( $instance['newsApiKeyword'] ) === 0 ) {
				echo '<span class="error">Please enter News Api keyword.</span>';
			}
			if ( strlen( $instance['newsApiKey'] ) === 0 ) {
				echo strlen( $instance['newsApiKeyword'] ) === 0 ? '<br/>' : '';
				echo '<span class="error">Please enter News Api Key</span>';
			}
		}
		echo '</div>';
		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$defaults = [
			'title' => 'News API Post Feed',
			'total' => '5',
		];

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:<br/>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo $instance['title']; ?>"/>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'newsApiKey' ); ?>">Enter News Api Key:<br/>
                <input class="widefat" id="<?php echo $this->get_field_id( 'newsApiKey' ); ?>"
                       name="<?php echo $this->get_field_name( 'newsApiKey' ); ?>" type="text"
                       value="<?php echo $instance['newsApiKey']; ?>"/>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'newsApiKeyword' ); ?>">Enter News Topic keyword:<br/>
                <input class="widefat" id="<?php echo $this->get_field_id( 'newsApiKeyword' ); ?>"
                       name="<?php echo $this->get_field_name( 'newsApiKeyword' ); ?>" type="text"
                       value="<?php echo $instance['newsApiKeyword']; ?>"/>
            </label>
        </p>


        <p>
            <label for="<?php echo $this->get_field_id( 'total' ); ?>">Number of Post to show:<br/>
                <input class="" id="<?php echo $this->get_field_id( 'total' ); ?>"
                       name="<?php echo $this->get_field_name( 'total' ); ?>" type="number" max="10"
                       value="<?php echo $instance['total']; ?>"/>
            </label>
        </p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;
		$instance['title']          = $new_instance['title'];
		$instance['newsApiKey']     = $new_instance['newsApiKey'];
		$instance['newsApiKeyword'] = $new_instance['newsApiKeyword'];
		$instance['total']          = $new_instance['total'];
		$instance['total']          = $new_instance['total'];

		if ( function_exists( 'icl_register_string' ) ) {
			define( 'NEWSAPI_FEED_TEXT_DOMAIN', 'Newsapi Post Feed Widget' );
			icl_register_string( XCLOUD_TEXT_DOMAIN, $instance['title'], $instance['title'] );
		}

		return $instance;
	}

}