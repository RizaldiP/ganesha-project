<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request): JsonResponse
    {
        $events = CalendarEvent::with('user')->latest()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->is_all_day
                    ? $event->start_date->format('Y-m-d')
                    : $event->start_date->format('Y-m-d') . 'T' . ($event->start_time ?? '00:00'),
                'end' => $event->end_date
                    ? ($event->is_all_day
                        ? $event->end_date->format('Y-m-d')
                        : $event->end_date->format('Y-m-d') . 'T' . ($event->end_time ?? '23:59'))
                    : null,
                'allDay' => (bool) $event->is_all_day,
                'color' => $event->color,
                'textColor' => '#ffffff',
                'description' => $event->description,
                'user_name' => $event->user->name,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'color' => 'nullable|string|max:20',
            'is_all_day' => 'boolean',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['color'] ??= '#6366f1';

        $event = CalendarEvent::create($data);

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->is_all_day
                    ? $event->start_date->format('Y-m-d')
                    : $event->start_date->format('Y-m-d') . 'T' . ($event->start_time ?? '00:00'),
                'end' => $event->end_date
                    ? ($event->is_all_day
                        ? $event->end_date->format('Y-m-d')
                        : $event->end_date->format('Y-m-d') . 'T' . ($event->end_time ?? '23:59'))
                    : null,
                'allDay' => (bool) $event->is_all_day,
                'color' => $event->color,
                'textColor' => '#ffffff',
                'description' => $event->description,
                'user_name' => $event->user->name,
            ],
        ]);
    }

    public function update(Request $request, CalendarEvent $calendarEvent): JsonResponse
    {
        if ($calendarEvent->user_id !== $request->user()->id && !$request->user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'color' => 'nullable|string|max:20',
            'is_all_day' => 'boolean',
        ]);

        $calendarEvent->update($data);
        $calendarEvent->load('user');

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $calendarEvent->id,
                'title' => $calendarEvent->title,
                'start' => $calendarEvent->is_all_day
                    ? $calendarEvent->start_date->format('Y-m-d')
                    : $calendarEvent->start_date->format('Y-m-d') . 'T' . ($calendarEvent->start_time ?? '00:00'),
                'end' => $calendarEvent->end_date
                    ? ($calendarEvent->is_all_day
                        ? $calendarEvent->end_date->format('Y-m-d')
                        : $calendarEvent->end_date->format('Y-m-d') . 'T' . ($calendarEvent->end_time ?? '23:59'))
                    : null,
                'allDay' => (bool) $calendarEvent->is_all_day,
                'color' => $calendarEvent->color,
                'textColor' => '#ffffff',
                'description' => $calendarEvent->description,
                'user_name' => $calendarEvent->user->name,
                'start_time' => $calendarEvent->start_time,
                'end_time' => $calendarEvent->end_time,
            ],
        ]);
    }

    public function destroy(Request $request, CalendarEvent $calendarEvent): JsonResponse
    {
        if ($calendarEvent->user_id !== $request->user()->id && !$request->user()->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $calendarEvent->delete();

        return response()->json(['success' => true]);
    }
}
