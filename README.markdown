# Spam Question Filter

- Version: 1.0
- Author: Adrian Kummerländer 
- Build Date: 15th July 2012
- Requirements: Symphony 2.*

## Description

Simple filter to check if a question like "2 + 3 = ?" was correctly answered by the user. 
This can be used as a replacement for other spam prevention methods like captchas.

## Installation

1. Place the `spamquestionfilter` folder in your Symphony `extensions` directory.
2. Go to _System > Extensions_, select "Spam Question Filter", choose "Enable" from the with-selected menu, then click Apply.

## Usage

1. Go to _Blueprints > Components_ and click the name of the event whose input you want to filter.
2. In the "Filter Rules" field, select "Spam Question Filter"
3. Save your event
4. Add the custom event "Spamquestion" to the page containing your form
5. Now you will have to add three new fields to your frontend form, see the expample below for details.

## Example-Form

	<form method="post" action="" enctype="multipart/form-data">
	<!-- This is the anti-spam question  -->
		<xsl:value-of select="events/spamquestion/part1"/> plus <xsl:value-of select="events/spamquestion/part2"/> equals: <input name="fields[number]" type="text"/>

	<!-- These fields should be hidden so the user won't be confused by them  -->
		<input name="fields[check1]" type="text" value="{events/spamquestion/part1}" />
		<input name="fields[check2]" type="text" value="{events/spamquestion/part2}" />
	</form>
