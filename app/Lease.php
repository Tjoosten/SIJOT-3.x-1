<?php

namespace Sijot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lease
 *
 * @package App
 */
class Lease extends Model
{
    use SoftDeletes;

    /**
     * Mass-assign fields for the database table.
     *
     * @var array
     */
    protected $fillable = [
        'status_id', 'opener_id', 'afsluiter_id', 'kapoenen_lokaal', 
        'welpen_lokaal', 'jongGivers_lokaal', 'givers_lokaal', 
        'jins_lokaal', 'grote_zaal', 'toiletten', 'groeps_naam', 
        'contact_email', 'tel_nummer', 'eind_datum', 'start_datum'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['eind_datum', 'start_datum', 'updated_at', 'created_at', 'deleted_at'];

    /**
     * Format the timestamp format.
     *
     * @param  string $date The start time from the form
     * @return string
     */
    public function setStartDateAttribute($date)
    {
        // Use with Carbon instance:
        // -------
        // Carbon::createFromFormat('H:i', $date)->format('H:i');
        return $this->attributes['start_datum'] = strtotime(str_replace('/', '-', $date));
    }

    /**
     * Format the timestamp format.
     *
     * @param  string $date The start time from the form
     * @return string
     */
    public function setEndDateAttribute($date)
    {
        // Use with Carbon instance:
        // -------
        // Carbon::createFromFormat('H:i', $date)->format('H:i');
        return $this->attributes['eind_datum'] = strtotime(str_replace('/', '-', $date));
    }

    public function getDobAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    /**
     * Get the notitions for the given domain lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notitions()
    {
        return $this->belongsToMany(Notitions::class)->withTimestamps();
    }

    /**
     * Get the 'opener' for the lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function opener() 
    {
        return $this->belongsTo(User::class, 'opener_id');
    }

    /**
     * Get the 'afsluiter' for the lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function afsluiter() 
    {
        return $this->belongsTo(User::class, 'afsluiter_id'); 
    }
}
