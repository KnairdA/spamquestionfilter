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
			if(!in_array('spam-question', $context['event']->eParamFILTERS)) return;

			$correct_answer = false;

			if ( isset($context['fields']['check1']) &&
			     isset($context['fields']['check2']) &&
			     isset($context['fields']['number']) )
			{
				$result = $context['fields']['check1'] + $context['fields']['check2'];

				if ( $result == $context['fields']['number'] ) 
				{
					$correct_answer = true;
				}
			}

			if(in_array('spam-question', $context['event']->eParamFILTERS) && $correct_answer == false) {
				$context['messages'][] = array(
					'spam', false, __("The answer to the spam question was found to be incorrect")
				);
			}
		}

	}
