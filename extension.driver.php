<?php

	Class Extension_SpamQuestionFilter extends Extension {

		public function about() {
			return array(
				'name' => 'Spam Question Filter',
				'version' => '1.0',
				'release-date' => '2012-07-15',
				'author' => array(
					'name' => 'Adrian Kummerländer',
					'website' => 'http://adriank.redirectme.net',
					'email' => 'adrian.kummerlaender@piraten-konstanz.de'
				),
				'description' => 'Protect your events against spam using a simple math question'
			);
		}

		public function getSubscribedDelegates() {
			return array(
				array(
					'page' => '/blueprints/events/new/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'appendEventFilter'
				),
				array(
					'page' => '/blueprints/events/edit/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'appendEventFilter'
				),
				array(
					'page' => '/blueprints/events/new/',
					'delegate' => 'AppendEventFilterDocumentation',
					'callback' => 'addFilterDocumentationToEvent'
				),
				array(
					'page' => '/frontend/',
					'delegate' => 'EventPreSaveFilter',
					'callback' => 'eventPreSaveFilter'
				),
			);
		}

		public function appendEventFilter(array $context) {
			$context['options'][] = array(
				'spam-question',
				is_array($context['selected']) ? in_array('spam-question', $context['selected']) : false,
				'Spam Question Filter'
			);
		}

		public function eventPreSaveFilter(array $context) {
			if ( in_array('spam-question', $context['event']->eParamFILTERS) ) {
				$correct_answer = false;

				if ( isset($context['fields']['spam']) &&
				     isset($context['fields']['number']) )
				{
					$decodedCheck = base64_decode( $context['fields']['spam'] );
					$elements = explode( '|', $decodedCheck );

					$check1 = $elements[0];
					$check2 = $elements[1];
					$userResult = $context['fields']['number'];

					if ( is_numeric($check1) &&
					     is_numeric($check2) &&
					     is_numeric($userResult) )
					{
						$result = $check1 + $check2;

						if ( $result == $userResult ) 
						{
							$correct_answer = true;
						}
					}
				}

				if ( $correct_answer == true ) {
					$context['messages'][] = array(
						'spam', true, __("The answer to the spam question was correctly answered - we seem to be dealing with a human.")
					);
				}
				else {
					$context['messages'][] = array(
						'spam', false, __("The answer to the spam question was found to be incorrect.")
					);
				}
			}
		}

		public function addFilterDocumentationToEvent($context) {
			if (!in_array('spam-question', $context['selected'])) return;

			$context['documentation'][] = new XMLElement('h3', 'Spam Question Filter');
			$context['documentation'][] = new XMLElement('p', 'Prevents spam by asking the user to answer a simple math question like "2 + 4 = ?".');
			$context['documentation'][] = new XMLElement('p', 'To use this filter you will have to add the custom "Spamquestion" event to your page and build your form according to the example below:');

			$code = '<form method="post" action="" enctype="multipart/form-data">
<!-- This is the anti-spam question  -->
    <xsl:value-of select="events/spamquestion/part1"/> plus <xsl:value-of select="events/spamquestion/part2"/> equals: <input name="fields[number]" type="text"/>

<!-- These fields should be hidden so the user won\'t be confused by them  -->
    <input name="fields[spam]" type="text" value="{events/spamquestion/check}" />
</form>';

			$context['documentation'][] = contentBlueprintsEvents::processDocumentationCode($code);
			$context['documentation'][] = new XMLElement('p', 'For further information concerning the installation and usage of this extension check the <a href="https://github.com/KnairdA/spamquestionfilter">README</a>.');

		}

	}

?>
