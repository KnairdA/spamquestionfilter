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
4. Now you will have to add three new fields to your frontend form, see the expample below for details.

## Example-Form

	<!-- You have to add the math extension - otherwise we will not be able to generate the needed random numbers  -->
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

	<!-- These fields should be hidden so the user won't be confused by them  -->
		<input name="fields[check1]" type="text" value="{$num1}" />
		<input name="fields[check2]" type="text" value="{$num2}" />
	</form>
