# SASS - How To

  

#### This is small tutorial what do files and folders represent

  

### Folder structure

```

sass/

|__base/

| |__ _fonts.scss

| |__ _grid.scss

| |__ _print.scss

| |__ _reboot.scss

| |__ _root.scss

| |__ _type.scss

| |.. ...

|__components/

| |__atoms/

| |__modules/

| |__pages/

| | |.. ...

|__layout/

| |.. ...

|__mixins/

| |__grid/

| | |__ _grid.scss

| | |__ _grid-breakpoint.scss

| | |__ _grid-framework.scss

| | |__ _grid.scss

| |__ _display.scss

| |__ _flex.scss

| |__ _image.scss

| |__ _screen-reader.scss

| |.. ...

|__utils/

| |__ _functions.scss

| |.. ...

|_vars/

| |__ _base.scss

| |__ _colors.scss

| |__ _grid.scss

| |__ _options.scss

| |__ _type.scss

| |.. ...

|__vendor/

| |.. ...

|__ _base.scss

|__ _components.scss

|__ _mixins.scss

|__ _layout.scss

|__ _utils.scss

|__ _vars.scss

|__ _vendor.scss

|__ style.scss

```

  

-  **Base** should include global styles settings, grid, print styles, typography etc...

-  **Mixins** should have all mixins inside (for creating grid, custom helpful mixins etc...)

-  **Components** should have all reusable components across the site

-  **Utils** utility folder - typically only functions and some helpful sass mixins used for testing/developing and less in project

-  **Vars** all variables across site (splitted in as much subfolders as makes sense)

-  **Vendor** contain all SCSS files from external libraries and frameworks. (ACF styles will be put in special file (not style.css))

-  *NOTE:* Feel free to add more folders/files depending on project needs

  

### Formatting

  

#### Indentation and spacing

- Be consistent with indentation

- Use a single space after a property's name colon

- Use one whitespace between an element and a bracket

- Use one selector per line, one rule per line

```scss

.button,
.link {
	color:  $white;
	text-decoration:  none;
}

```

- Use Line-Break between styles, new element and after closing the bracket

```scss

.button {
	background-color:  $red;
	
	&.button-large {
		width: 100%;
	}

	&:hover {
		background-color:  $black;
	}

	.link {
		color:  $blue;
	}
}

```

- No space before and after single line comment that wraps styles section

```scss

// Button - START
.button {
	background-color:  $black;
}
// Button - END

```

- Comments that are in the same line as code are perceded by one space

```scss

.button-move {
	cursor:  pointer; // Use as falback
	cursor:  grab;
}

```

- Commas in function arguments should be followed by a single space

```scss

.button {
	box-shadow: 0 1px 1px 2px rgba($black, .2);
	transition:  all 2.5s cubic-bezier(1, 0, 0, 1);
}

```

### Naming
- Follow the ABEM methodology - [a/m/p]-blockName__elementName -modifierName

```html

<!-- Module -->
<div  class="m-card">
	<!-- Module element -->
	<div  class="m-card__content">
		...
	</div>
</div>

<!-- Module with modifier-->
<div  class="m-card -rounded">
	<!-- Module element -->
	<div  class="m-card__content">
		...
	</div>
</div>

```

### Nesting and Ordering
- Selectors should be nested in the following way: pseudo-elements, pseudo-selectors, @inlcude blocks, child selectors. Order can be changed if certain child selectors are affected by parent hover, for example.

```scss

.button {
	@include  hide-text;
	width: 100px;
	color:  $white;
	background-color:  $black;  

	&::before {
		...
	}

	&:hover {
		...
	}
}
```

- Nesting depth should not be greater than 5

- Selector depth should not be greater than 3

  

### Style Rules
- Do not use units after 0 values unless they are required

```scss

// Bad example

.button {
	box-shadow: 0px 0px 1px $black;
	margin: 0px;
}

// Good example

.button {
	box-shadow: 0 0 1px $black;
	margin: 0;
}

```

- Always use hex notation for color variables

  

```scss

// Bad example

$black:  black;

$white:  rgb(255, 255, 255);

$red:  hsl(0, 100%, 50%);

// Good example

$black: #000;

$white: #fff;

$red: #f00;

```

- Use color variables as function arguments

  

```scss

background-color: rgba($black, .2);
color: lighten($black, 80%);

```

- Always use single quotes

  
```scss

&::before {
	content:  '';
}

```

- Never use vendor prefixes
- 
  
### Grid
- Grid variables (media and wrapper width) is located in *_vars/_grid.scss*

- Grid classes are used by adding _ in front of each breakpoint:
```scss

._wr // - wrapper
._w  // - wrap - used to wrap columns
._12 // - 12 columns on all screen sizes
._m6 // - 6 columns on all screen sizes above m breakpoint
.ofs_m2 // offset column by 2 above m breakpoint
.ord_m2 // place element on second place above m breakpoint

```

- Grid mixins use defined breakpoints defined inside */scss/_vars/_grid.scss*:
```scss

// Target screen sizes below defined m breakpoint
@include media-down(m) {
	background-color: $black;
}

// Target screen sizes above defined m breakpoint
@include media-up(m) {
	background-color: $black;
}

// Target only m breakpoint
@include media-only(m) {
	background-color: $black;
}

// Target screen sizes between defined m and l breakpoint
@include media-between(m, l) {
	background-color: $black;
}

// Target landscape screen sizes
@include media-landscape {
	background-color: $black;
}

// Target laptop screen sizes
@include media-laptop {
	background-color: $black;
}

```
