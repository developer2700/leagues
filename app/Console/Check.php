<?php



namespace Insider\Football\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\DuplicateDataException;
use App\Services\InsiderFootballService;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Football:Check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Football Match';

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

        $service = new InsiderFootballService();
        try {
            $this->info("Start");
            $service->checkWeek();
        } catch (EntryNotFoundException $e) {
            $this->error($e->getMessage());
        } catch (ValidationException $e) {
            $this->error($e->getMessage());
        } catch (DuplicateDataException $e) {
            $this->error($e->getMessage());
        }
        $this->info("End");
        die();
    }
}
