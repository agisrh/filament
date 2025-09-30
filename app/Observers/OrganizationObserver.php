<?php

namespace App\Observers;

use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "updating" event.
     */
    public function updating(Organization $organization): void
    {
        Log::info('OrganizationObserver updating triggered', [
            'organization_id' => $organization->id,
            'old_logo' => $organization->getOriginal('logo'),
            'new_logo' => $organization->logo,
            'is_dirty' => $organization->isDirty('logo'),
        ]);

        if ($organization->isDirty('logo')) {
            $oldLogo = $organization->getOriginal('logo');
            
            if ($oldLogo) {
                Log::info('Checking old logo file', [
                    'old_logo' => $oldLogo,
                    'exists' => Storage::disk('local')->exists($oldLogo),
                ]);
                
                // Hapus file lama jika ada
                if (Storage::disk('local')->exists($oldLogo)) {
                    $deleted = Storage::disk('local')->delete($oldLogo);
                    Log::info('Old logo deleted successfully', [
                        'old_logo' => $oldLogo,
                        'deleted' => $deleted,
                    ]);
                } else {
                    Log::warning('Old logo file not found', [
                        'old_logo' => $oldLogo,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Organization "updated" event.
     */
    public function updated(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "deleted" event.
     */
    public function deleted(Organization $organization): void
    {
        // Delete logo file when organization is deleted
        if ($organization->logo) {
            Storage::disk('local')->delete($organization->logo);
            Log::info('Logo deleted on organization deletion', [
                'organization_id' => $organization->id,
                'logo' => $organization->logo,
            ]);
        }
    }

    /**
     * Handle the Organization "restored" event.
     */
    public function restored(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "force deleted" event.
     */
    public function forceDeleted(Organization $organization): void
    {
        // Delete logo file when organization is force deleted
        if ($organization->logo) {
            Storage::disk('local')->delete($organization->logo);
            Log::info('Logo deleted on organization force deletion', [
                'organization_id' => $organization->id,
                'logo' => $organization->logo,
            ]);
        }
    }
}
