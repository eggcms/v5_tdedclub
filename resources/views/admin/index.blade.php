@extends('layouts.admin')
@section('content')
<h1>Blogs manage</h1>
<table>
    <tr>
        <th>
            <form action="" method="post">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="title" class="form-control" placeholder="Enter title" id="title">
                </div>
                <div class="form-group form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox"> Remember me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
            
        </th>
    </tr>
</table>
RGB color space
RGB color space or RGB color system, constructs all the colors from the combination of the Red, Green and Blue colors.

The red, green and blue use 8 bits each, which have integer values from 0 to 255. This makes 256*256*256=16777216 possible colors.

RGB ≡ Red, Green, Blue

Each pixel in the LED monitor displays colors this way, by combination of red, green and blue LEDs (light emitting diodes).

When the red pixel is set to 0, the LED is turned off. When the red pixel is set to 255, the LED is turned fully on.

Any value between them sets the LED to partial light emission.

RGB color format & calculation
RGB code has 24 bits format (bits 0..23):
@endsection