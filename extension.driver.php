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

				if ( $correct_answer == true ) {
					$context['messages'][] = array(
						'spam', true, __("The answer to the spam question was correctly answered - we seem too be dealing with a human.")
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
			$context['documentation'][] = new XMLElement('p', 'To use this filter you will have to make some changes to your markup - see the example below for further information:');

			$code = '<!-- You have to add the math extension - otherwise we will not be able to generate the needed random numbers  -->
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:math="http://exslt.org/math"
	extension-element-prefixes="math">

<!-- Fill the two numbers with random values  -->
<xsl:param name="num1" select="floor(math:random()*10) + 1"/>
<xsl:param name="num2" select="floor(math:random()*10) + 1"/>

<!-- Test-Form  -->
<form method="post" action="" enctype="multipart/form-data">
	<!-- This is the anti-spam question  -->
	<xsl:value-of select="$num1"/> plus <xsl:value-of select="$num2"/> equals: <input name="fields[number]" type="text"/>

	<!-- These fields should be hidden so the user won\'t be confused by them  -->
	<input name="fields[check1]" type="text" value="{$num1}" />
	<input name="fields[check2]" type="text" value="{$num2}" />
</form>';

			$context['documentation'][] = contentBlueprintsEvents::processDocumentationCode($code);

		}

	}

?>
