<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicketType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use Illuminate\Support\Collection;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $events = QueryBuilder::for(Event::class)
            ->defaultSort('title')
            ->allowedSorts(['title','venue'])
            ->allowedFilters(['title','venue'])
            ->paginate(10)
            ->withQueryString();
        
        return Inertia::render('Events/Index', [
            'events' => $events
        ])->table(function(InertiaTable $table){
            $table
            ->defaultSort('title')
            ->column(key: 'title', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'venue', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'country', searchable: false, sortable: true, canBeHidden: false)
            ;
        }); 
    }


    public function home()
    {
        return Inertia::render('Home', [
            'events' => Event::paginate(10)->through(function($events){
                return [
                    'id' => $events->id,
                    'title' => $events->title,
                    'venue'=>$events->venue,
                    'description'=> $events->description,
                    'image' => Storage::url($events->image),
                    'url' => route('event.show', $events),
                    'start_date'=> $this->getDate($events->start_date),
                    'end_date'=> $this->getDate($events->end_date),
                    'start_time'=> $events->start_time,
                    'end_time'=> $events->end_time,
                ];
            })
        ]);
    }

    public function getDate($value){
        $date=Carbon::createFromFormat('Y-d-m', $value);
        return $date->format('l').','.$date->format('F').','.$date->format('Y');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
        
        $tickets=empty(EventTicketType::where('event_id',$event->id)->get())? $tickets=null : EventTicketType::where('event_id',$event->id)->get(); 
        return Inertia::render('EventDetails',[
            'event'=> $event,
            'img'=>Storage::url($event->image),
            'tickets'=>$tickets,
            'min_quantity'=>1,
            'max_quantity'=>3
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
