<?php

namespace Convo\Core\Workflow;

use Convo\Core\DataItemNotFoundException;
use Convo\Core\Media\IAudioFile;
interface IMediaSourceContext extends \Convo\Core\Workflow\IServiceContext
{
    const DEFAULT_MEDIA_INFO = ['current' => null, 'next' => null, 'count' => 0, 'last' => \true, 'first' => \true, 'song_no' => 0, 'loop_status' => \false, 'shuffle_status' => \false];
    /**
     * @return \Iterator
     */
    public function getSongs();
    // STATE
    /**
     * Are there any results
     * @return bool
     */
    public function isEmpty() : bool;
    /**
     * Is there next available.
     * @return bool
     */
    public function isLast() : bool;
    /**
     * Returns current results total count.
     * @return int
     */
    public function getCount() : int;
    /**
     * Returns the next song if available and sets the pointer. Will throw DataItemNotFoundException if no results, or single result with loop status off
     * @return IAudioFile
     * @throws DataItemNotFoundException
     */
    public function next() : IAudioFile;
    /**
     * Returns the current mp3 file. Will throw DataItemNotFoundException if list is empty.
     * @return IAudioFile
     * @throws DataItemNotFoundException
     */
    public function current() : IAudioFile;
    // ACTIONS
    /**
     * Moves pointer to the previous song. If result is empty or we have single result with loop off, will throw DataItemNotFoundException
     * @throws DataItemNotFoundException
     */
    public function movePrevious();
    /**
     * Moves pointer to the next song. If result is empty or we have single result with loop off, will throw DataItemNotFoundException
     * @throws DataItemNotFoundException
     */
    public function moveNext();
    /**
     * Moves pointer to the target song. If inedx is out of range, will throw DataItemNotFoundException
     * @param int $offset
     * @throws DataItemNotFoundException
     */
    public function seek($index);
    /**
     * Rewinds internal pointer to the first song in playlist.
     */
    public function rewind();
    // SETTINGS
    /**
     * Returns the current song offset in milliseconds.
     * @return int
     */
    public function getOffset() : int;
    /**
     * Sets the current song offset in milliseconds.
     * @param int $offset
     */
    public function setOffset($offset);
    /**
     * Marks player as stopped. If offset is provided, it will be also saved.
     * @param int $offset
     */
    public function setStopped($offset = -1);
    /**
     * Marks player as playing.
     */
    public function setPlaying();
    /**
     * Sets the status of the loop playback mode.
     * @param bool $loopStatus
     */
    public function setLoopStatus($loopStatus);
    /**
     * Returns the status of the loop playback mode.
     * @return bool
     */
    public function getLoopStatus() : bool;
    /**
     * Sets the status of the shuffle playback mode.
     * @param bool $loopStatus
     */
    public function setShuffleStatus($shuffleStatus);
    /**
     * Returns player's shuffle mode
     * @return bool
     */
    public function getShuffleStatus() : bool;
    /**
     * Returns the current media info. Associative array as defined in DEFAULT_MEDIA_INFO 
     * @return array
     */
    public function getMediaInfo() : array;
}
