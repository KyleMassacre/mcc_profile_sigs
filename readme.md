
# Profile Signature

A brief description of what this project does and who it's for


## Installation

### SQLs

#### Run in database
```sql
  alter table users
    add profile_signature TEXT default NULL null;
```
### Add to preferences.php

#### Find `switch` statement towards the top of file and add before the `default` statement
```php
case 'signaturechange':
    signature_change();
    break;
case 'dosignaturechange':
    do_signature_change();
    break;
```

#### Insert into `prefs_home` function
```html
<a href='preferences.php?action=signaturechange'>Change Signature</a>
```

#### At the bottom of file, find `$h->endpage();`, add right above that
```php
function signature_change()
{
    global $ir;
    $code = request_csrf_code('prefs_sigchange');
    echo "
	<h3>Profile Signature Change</h3>
	<div>
	    <span>Signature (you may use BBcode)</span>
	    <form action='?action=dosignaturechange' method='post'>
                <textarea rows='10' cols='50' name='profile_signature'>{$ir['profile_signature']}</textarea>
            <br />
            <input type='hidden' name='verf' value='{$code}' />
            <input type='submit' value='Change Info' />
	    </form>
    </div>
   ";
}

function do_signature_change()
{
    global $db, $userid, $h, $bbc;
    $maxLength = 250; // change this to whatever you wish
    if (! isset($_POST['verf']) || ! verify_csrf_code('prefs_sigchange', stripslashes($_POST['verf']))) {
        csrf_error('signaturechange');
    }
    $_POST['profile_signature'] = $db->escape(strip_tags(stripslashes($_POST['profile_signature'])));
    if(strlen($_POST['profile_signature']) > $maxLength)
    {
        echo 'You may only have a profile signature consisting of '. $maxLength .' characters or less.
        <br />&gt; <a href="?action=signaturechange">Go Back</a>';
        die($h->endpage());
    }

    $db->query("update users set profile_signature = '{$_POST['profile_signature']}' where userid = {$userid}");
    echo "<h3>Profile Signature</h3>";
    echo "<div>
        {$bbc->bbcode_parse(htmlentities($_POST['profile_signature'], ENT_QUOTES))}
    </div>";
    echo 'Signature Info changed!<br />
    &gt; <a href="index.php">Go Home</a>';



}
```

#### Edit `viewuser.php`
+ Find the query towards the top of the page `$q = $db->query(...)` and add the column `profile_signature` in the query.

+ Find the closing `</table>` tag and insert this right before it
```php
echo '
    </tr>
    <tr>
        <td class="h" colspan="3" style="text-align: center">User Signature</td>
    </tr>
    <tr>
        <td colspan="3">'. $bbc->bbcode_parse(htmlentities($r['profile_signature'], ENT_QUOTES)) .'</td>
    </tr>
```

#### Side Notes:
I use a different BB Code parser loaded by `composer`, so you will have to get the `BBCode` engine loaded into this file and also your `viewuser.php` page in order to get this to work properly.
I did load the BB Code parser in this in the `shortcode` directory, and the `bbcode.php` I use

#### Helpers
I created a helper function that you can put in your `global_func.php` so instead of using `htmlentities($text, ENT_QUOTES, 'UTF-8')` you can just use `_e($text)`
In `global_func.php` add:
```php
function _e($text)
{
    return htmlentities($text, ENT_QUOTES, "UTF-8");
}
```
## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@kylemassacre](https://www.github.com/kylemassacre)

