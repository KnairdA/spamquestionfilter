<?php

	require_once(TOOLKIT . '/class.event.php');

	Class eventSpamquestion extends Event {
		
		const ROOTELEMENT = 'spamquestion';

		public static function about() {
			return array(
				'name' => 'Spamquestion',
				'author' => array(
					'name' => 'Adrian Kummerlaender',
					'website' => 'http://adriank.redirectme.net',
					'email' => 'adrian.kummerlaender@piraten-konstanz.de'
				),
				'version' => '1.0',
				'release-date' => '2012-07-22'
			);
		}

		public static function getSource() {
			return false;
		}

		public static function allowEditorToParse() {
			return false;
		}

		public function load() {
			return $this->__trigger();
		}

		protected function __trigger() {
			$result = new XMLElement(self::ROOTELEMENT);

			$rnd1 = rand(1,10);
			$rnd2 = rand(1,10);

			$check = base64_encode($rnd1 . '|' . $rnd2);

			$part1 = new XMLElement('part1');
			$part1->setValue( $rnd1 );

			$part2 = new XMLElement('part2');
			$part2->setValue( $rnd2 );

			$part3 = new XMLElement('check');
			$part3->setValue( $check );

			$result->appendChild($part1);
			$result->appendChild($part2);
			$result->appendChild($part3);

			return $result;
		}

	}

?>
