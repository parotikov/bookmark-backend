<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bookmark;
use App\Services\BookmarkService;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Carbon\Carbon;

class CheckUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookmarks:check {--invalid} {--mute} {--exclude=} {--include=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks, if url has 404 http status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $currentTime = Carbon::now();
      // echo $currentTime->subMonth() . PHP_EOL;

      $selectValid = $this->option('invalid') ? false : true;

      $query = Bookmark::query();

      if($this->option('exclude')) $query->where('title', 'NOT LIKE', '%' . $this->option('exclude') . '%');
      if($this->option('include')) $query->where('title', 'LIKE', '%' . $this->option('include') . '%');

      $query->where('is_valid', $selectValid);

      //get older than month or without validate date
      $query->where(function ($query) use($currentTime) {
        $query->where('validated_at', '<', $currentTime->subMonth())
            ->orWhereNull('validated_at');
      });
      
      $bookmarks = $query->get();

      $bar = $this->output->createProgressBar(count($bookmarks));
      $bar->start();

      foreach ($bookmarks as $bookmark) {

          // $this->line('checking ' . $bookmark->title);
          // if($currentTime->diffInDays($bookmark->validated_at) < 30) continue;
          // $this->info($currentTime->diffInDays($bookmark->validated_at));

          if(BookmarkService::isUrl404($bookmark->url))   
          {
            $bookmark->is_valid = false;
            
            if(!$this->option('mute')) $this->error($bookmark->title . ' is not valid ');
          }
          else {
            if(!$this->option('mute')) $this->info($bookmark->title . ' is valid ');
          }
          $bar->advance();

          $bookmark->validated_at = Carbon::now();
          $bookmark->save();
      }
      $bar->finish();
    }

}
