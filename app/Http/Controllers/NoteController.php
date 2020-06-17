<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    private $array = [
        'error' => '',
        'result' => [],
    ];

    /**
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function all()
    {
        $notes = Note::all();

        foreach ($notes as $note) {
            $this->array['result'][] = [
                'id' => $note->id,
                'title' => $note->title,
            ];
        }

        return response()->json($this->array)->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function one($id)
    {
        $note = Note::find($id);

        if ($note) {
            $this->array['result'] = $note;
            $status = JsonResponse::HTTP_OK;
        } else {
            $this->array['error'] = 'Note not found';
            $status = JsonResponse::HTTP_NOT_FOUND;
        }

        return response()->json($this->array)->setStatusCode($status);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function new(Request $request)
    {
        $title = $request->input('title');
        $body = $request->input('body');

        if ($title && $body) {
            $note = new Note();
            $note->title = $title;
            $note->body = $body;
            $note->save();

            $this->array['result'] = [
                'id' => $note->id,
                'title' => $note->title,
                'body' => $note->body,
            ];

            $status = JsonResponse::HTTP_OK;
        } else {
            $this->array['error'] = 'Unsent fields';
            $status = JsonResponse::HTTP_BAD_REQUEST;
        }

        return response()->json($this->array)->setStatusCode($status);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function edit(Request $request, $id)
    {
        $title = $request->input('title');
        $body = $request->input('body');

        if ($id && $title && $body) {
            $note = Note::find($id);

            if ($note) {
                $note->title = $title;
                $note->body = $body;
                $note->save();

                $this->array['result'] = [
                    'id' => $id,
                    'title' => $title,
                    'body' => $body
                ];

                $status = JsonResponse::HTTP_OK;
            } else {
                $this->array['error'] = 'Note not found';
                $status = JsonResponse::HTTP_NOT_FOUND;
            }
        } else {
            $this->array['error'] = 'Unset fields';
            $status = JsonResponse::HTTP_BAD_REQUEST;
        }

        return response()->json($this->array)->setStatusCode($status);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function delete($id)
    {
        $note = Note::find($id);

        if ($note) {
            $note->delete();
            $status = JsonResponse::HTTP_OK;
        } else {
            $this->array['error'] = 'Note not found';
            $status = JsonResponse::HTTP_NOT_FOUND;
        }

        return response()->json($this->array)->setStatusCode($status);
    }
}
