<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



 Route::prefix('/leagues/')->middleware(['Cors', 'Json'])->group(function () {

        Route::get('baseSettings', 'BaseSettingController@getAll');
        Route::get('baseSetting/{iId}', 'BaseSettingController@get');
        Route::post('baseSetting', 'BaseSettingController@set');
        Route::put('baseSetting/{iId}', 'BaseSettingController@update');
        Route::delete('baseSetting/{iId}', 'BaseSettingController@delete');

        Route::get('settings', 'SettingController@getAll');
        Route::get('setting/{iId}', 'SettingController@get');
        Route::post('setting', 'SettingController@set');
        Route::put('setting/{iId}', 'SettingController@update');
        Route::delete('Setting/{iId}', 'SettingController@delete');

        Route::get('teams', 'TeamController@getAll');
        Route::get('team/{iId}', 'TeamController@get');
        Route::post('team', 'TeamController@set');
        Route::put('team/{iId}', 'TeamController@update');
        Route::delete('team/{iId}', 'TeamController@delete');

        Route::get('players', 'PlayerController@getAll');
        Route::get('player/{iId}', 'PlayerController@get');
        Route::post('player', 'PlayerController@set');
        Route::put('player/{iId}', 'PlayerController@update');
        Route::delete('player/{iId}', 'PlayerController@delete');

        Route::get('contracts', 'ContractController@getAll');
        Route::get('contract/{iId}', 'ContractController@get');
        Route::post('contract', 'ContractController@set');
        Route::put('contract/{iId}', 'ContractController@update');
        Route::delete('contract/{iId}', 'ContractController@delete');

        Route::get('league/startWeek', 'LeagueController@startWeek');
        Route::get('league/endWeek', 'LeagueController@endWeek');

        Route::get('leagues', 'LeagueController@getAll');
        Route::get('league/{iId}', 'LeagueController@get');
        Route::post('league', 'LeagueController@set');
        Route::put('league/{iId}', 'LeagueController@update');
        Route::delete('league/{iId}', 'LeagueController@delete');

        Route::get('leagueTeams', 'LeagueTeamController@getAll');
        Route::get('leagueTeam/{iId}', 'LeagueTeamController@get');
        Route::get('leagueTeam:league_id/{iId}', 'LeagueTeamController@getByMatchId');
        Route::post('leagueTeam', 'LeagueTeamController@set');
        Route::put('leagueTeam/{iId}', 'LeagueTeamController@update');
        Route::delete('leagueTeam/{iId}', 'LeagueTeamController@delete');

        Route::get('weeks', 'WeekController@getAll');
        Route::get('week/{iId}', 'WeekController@get');
        Route::post('week', 'WeekController@set');
        Route::put('week/{iId}', 'WeekController@update');
        Route::delete('week/{iId}', 'WeekController@delete');

        Route::get('matches', 'MatchController@getAll');
        Route::get('match/{iId}', 'MatchController@get');
        Route::post('match', 'MatchController@set');
        Route::put('match/{iId}', 'MatchController@update');
        Route::delete('match/{iId}', 'MatchController@delete');

        Route::get('events', 'EventController@getAll');
        Route::get('event/{iId}', 'EventController@get');
        Route::post('event', 'EventController@set');
        Route::put('event/{iId}', 'EventController@update');
        Route::delete('event/{iId}', 'EventController@delete');

    });
