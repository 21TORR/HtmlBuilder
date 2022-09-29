1.x to 2.0
==========

* Removed custom type checks in favor of native ones. That means that a `\TypeError` will be thrown instead of custom
  * `InvalidAttributeValueException`
  * `UnexpectedTypeException`

* The handling of numeric HTML attributes changed to properly reflect the DOM. They are converted to `string` when setting.
