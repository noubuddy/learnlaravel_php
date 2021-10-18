<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'title', 'image'];
//    public function getTitleAttribute($value){
//        return ucfirst($value) . '.';
//    }

    public function setImageAttribute(UploadedFile $image){
       $path = $image->store('public');
       $this->image_path = Storage::url($path);
    }

    public function getSnippetAttribute(){
        return explode("\n\n", $this->body)[0];
    }

    public function getDisplayBodyAttribute() {
        return nl2br($this->body);
    }

    // @todo remove image when model is deleted
}
