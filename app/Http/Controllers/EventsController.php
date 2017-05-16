<?php

namespace Sijot\Http\Controllers;

use Sijot\Events;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Sijot\Http\Requests\EventValidator;

/**
 * Class EventsController
 *
 * @package Sijot\Http\Controllers
 */
class EventsController extends Controller
{
    /**
     * @var Events
     */
    private $events;

    /**
     * EventsController constructor.
     *
     * @param Events $events
     */
    public function __construct(Events $events)
    {
        $routes = ['index', 'delete', 'edit'];

        $this->middleware('auth')->only($routes);
        $this->middleware('forbid-banned-user')->only($routes);

        $this->events = $events;
    }

    /**
     * Store a new event in the database.
     *
     * @param   EventValidator $input   The user validation.
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function store(EventValidator $input)
    {
        if ($this->events->create($input->except(['_token']))) { // try to create the event.
            // The event has been created in the database.
            session()->flash('class', 'alert alert-success');
            session()->flash('message', 'Het evenement is aangemaakt.');
        }

        return back(302);
    }

    /**
     * Get the backend view for the events.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title']  = 'Evenementen';
        $data['events'] = $this->events->with(['author'])->paginate(15);

        return view('events.index', $data);
    }

    /**
     * Change the st§atus for the event.
     *
     * @param   integer $statusId
     * @param   integer $eventId    The id in the database for the event.
     * @return  mixed
     */
    public function status($statusId, $eventId)
    {
        try { // To find the event in the database.
            $event = $this->events->findOrFail($eventId);

            if ($event->update(['status' => $statusId])) { // Try to change the status.
                // The status has been updated.
                if ((int) $statusId === 0) { // Klad
                    session()->flash('class', 'alert alert-success');
                    session()->flash('message', 'Het evenement is gezet naar een klad versie.');
                } elseif ((int) $statusId === 1) { // Publicate
                    session()->flash('class', 'alert alert-success');
                    session()->flash('message', 'Het evenement is gepubliceerd.');
                }
            }

            return back(302);
        } catch (ModelNotFoundException $modelNotFoundException) { // Could not find the event in the database.
            return app()->abort(404);
        }
    }

    /**
     * Delete an even in the database.
     *
     * @param  integer $eventId The event id in the database.
     * @return mixed
     */
    public function delete($eventId)
    {
        // TODO: register route.

        try {
            $event = $this->events->findOrFail($eventId);

            if ($event->delete()) { // Try to delete the event.
                // The event has been deleted.
                session()->flash('class', 'alert alert-success');
                session()->flash('message', 'Het evenement is verwijderd');
            }

            return back(302);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return app()->abort(404);
        }
    }

    public function getById($eventId)
    {
        // TODO: Register route.

        try {
            return json_encode($this->events->findOrFail($eventId));
        } catch (ModelNotFoundException $modelNotFoundException) {
            return app()->abort(404);
        }
    }

    /**
     * Edit an event in the database.
     *
     * @param  EventValidator    $input     The user input validator.
     * @param  integer           $eventId   The event id in the database.
     * @return mixed
     */
    public function edit(EventValidator $input, $eventId)
    {
        // TODO: register route.

        try {
            $event = $this->events->findOrFail($eventId);

            if ($event->update($input->excetp(['_token']))) { // Try to update an event.
                // Event has been updated
                session()->flash();
                session()->flash();
            }

            return back(302);
        } catch (ModelNotFoundException $modelNotFoundException) { // Could not find the event in the database.
            return app()->abort(404);
        }
    }
}
