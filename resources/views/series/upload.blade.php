<x-layout title="Upload">
$tmpName = $_FILES['csv']['tmp_name'];
$csvAsArray = array_map('str_getcsv', file($tmpName));
</x-layout>