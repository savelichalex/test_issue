<?php
class BaseException extends Exception {};

class ConnectionMissesException extends BaseException {};

class TableMissesException extends BaseException {};

class TableCreationFailedException extends BaseException {};

class ValidationException extends BaseException {};

class CSVOpenFileException extends BaseException {};
?>