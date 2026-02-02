<?php

class Validator {
  public static function email(string $v): bool {
    return (bool)filter_var($v, FILTER_VALIDATE_EMAIL);
  }
}
