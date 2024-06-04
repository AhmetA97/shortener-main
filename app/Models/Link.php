<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'code'];


    // Shorten the URL
    public function shorten($url)
    {
        $this->url = $url;
        $this->code = $this->generateCode();
        $this->save();
    }

    // Generate a unique code
    private function generateCode()
    {
        // Get the last link's code from the database
        $lastLink = Link::select('code')->orderBy('id', 'desc')->first();

        // If there is no last link, then return 'a'
        if (!$lastLink) {
            return 'a';
        }

        $lastCode = $lastLink->code;

        // If the last code is 'z', then return 'aa'
        if ($lastCode === 'z') {
            return 'aa';
        }

        // Get the last character and remaining characters
        $lastCharacter = substr($lastCode, -1);
        $remainingCharacters = substr($lastCode, 0, -1);

        if ($lastCharacter === 'z') {
            // If the last character is 'z', increment the remaining characters and set the last character to 'a'
            $remainingCharacters = $this->incrementString($remainingCharacters);
            $lastCharacter = 'a';
        } else {
            // If the last character is not 'z', increment the last character
            $lastCharacter = $this->incrementString($lastCharacter);
        }

        // Return the generated code by combining the remaining characters and the last character
        return $remainingCharacters . $lastCharacter;
    }

    // Increment the string
    private function incrementString($string)
    {
        $length = strlen($string);
        $lastCharacter = $string[$length - 1];

        if ($lastCharacter === 'z') {
            if ($length === 1) {
                // If the string length is 1 and the last character is 'z', return 'aa'
                return 'aa';
            } else {
                // If the last character is 'z' and the string length is greater than 1,
                // increment the prefix string and append 'a' at the end
                $prefix = substr($string, 0, $length - 1);
                return $this->incrementString($prefix) . 'a';
            }
        } else {
            // If the last character is not 'z', increment it by converting its ASCII value to the next character
            $incrementedCharacter = chr(ord($lastCharacter) + 1);
            return substr_replace($string, $incrementedCharacter, -1);
        }
    }
}
