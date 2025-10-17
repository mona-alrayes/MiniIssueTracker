<?php

namespace App\Services;

use App\Models\Issue;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Exceptions\LabelOperationException;

/**
 * Handles operations related to issue-label relationships
 */
class IssueLabelService
{
    /**
     * Attach labels to an issue
     * @param Issue $issue
     * @param array $labelIds
     * @param mixed $actor
     * @return mixed
     * @throws LabelOperationException
     */
    public function attach(Issue $issue, array $labelIds, $actor = null)
    {
        try {
            return DB::transaction(function () use ($issue, $labelIds) {
                $issue->labels()->syncWithoutDetaching($labelIds);
                return $issue->load('labels')->labels;
            });
        } catch (QueryException $e) {
            throw new LabelOperationException('Failed to attach labels');
        }
    }

    /**
     * Detach a label from an issue
     * @param Issue $issue
     * @param int $labelId
     * @param mixed $actor
     * @throws LabelOperationException
     */
    public function detach(Issue $issue, int $labelId, $actor = null)
    {
        try {
            DB::transaction(function () use ($issue, $labelId) {
                $issue->labels()->detach($labelId);
            });
        } catch (QueryException $e) {
            throw new LabelOperationException('Failed to detach label');
        }
    }

    /**
     * Sync labels for an issue
     * @param Issue $issue
     * @param array $labelIds
     * @param mixed $actor
     * @return mixed
     * @throws LabelOperationException
     */
    public function sync(Issue $issue, array $labelIds, $actor = null)
    {
        try {
            return DB::transaction(function () use ($issue, $labelIds) {
                $issue->labels()->sync($labelIds);
                return $issue->load('labels')->labels;
            });
        } catch (QueryException $e) {
            throw new LabelOperationException('Failed to sync labels');
        }
    }
}
