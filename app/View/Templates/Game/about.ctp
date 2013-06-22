<h1>About ConwayJS.com</h1>

<p>The Game of Life is a cellular simulation created by British mathematician John Horton Conway. After defining an initial state, watch the cells evolve into still lifes, gliders, and long-lived patterns of chaos.</p>

<p>Cells are placed on an infinite grid according to some starting state. A live cell is represented by a black square and a dead cell is represented by a white square. The rules for cell birth and survival are governed by two rules:</p>

<ol>
    <li>Any live cell with 2 or 3 live neighbors survives.</li>
	<li>Any dead cell with exactly 3 live neighbors is born.</li>
</ol>

<p>You can also run the simulation with different rules. If you click on the gear icon in the upper right of the screen, you can choose a rule. Maze, for example, changes rule 2 to allow cells with 1, 2, 3, 4 or 5 neighbors to survive and creates a maze-like pattern that grows infinitely outward.</p>

<p>The most common notation for rules is SURVIVES/BORN. So Conway's Game of Life is "23/3" and Maze is "12345/3". Another common notation uses the letters B and S to indicate survival and birth, but in reverse order. The aforementioned rules would be "B3/S23" and "B3/S12345" respectively.</p>
	
<h1>Controls</h1>
<table class="shortcut-keys">
	<tr>
		<th>Draw:</th>
		<td>Click or click and hold<td>
	</tr>
	<tr>
		<th>Erase:</th>
		<td>Right click or right click and hold<td>
	</tr>
	<tr>
		<th>Start/Pause:</th>
		<td><span class="key">spacebar</span> OR <span class="key">Enter</span><td>
	</tr>
	<tr>
		<th>Reset:</th>
		<td><span class="key">R</span><td>
	</tr>
	<tr>
		<th>Pan Up/Down:</th>
		<td>
			<span class="key">↑</span> <span class="key">↓</span> OR
			scroll
		</td>
	</tr>
	<tr>
		<th>Pan Left/Right:</th>
		<td>
			<span class="key">←</span> <span class="key">→</span> OR
			<span class="key">shift</span>+scroll
		</td>
	</tr>
	<tr>
		<th>Zoom In/Out:</th>
		<td><span class="key">+</span> <span class="key">-</span></td>
	</tr>
	<tr>
		<th>Zoom to Fit:</th>
		<td><span class="key">F</span></td>
	</tr>
	<tr>
		<th>Faster/Slower:</th>
		<td><span class="key">&lt;</span> <span class="key">&gt;</span></td>
	</tr>
	<tr>
		<th>Next Tick:</th>
		<td><span class="key">T</span></td>
	</tr>
</table>

<h1>Source Code</h1>

<p>The JavaScript for this version of the Game of Life is available on <a target="_blank" href="https://github.com/kensnyder/ConwaysGameOfLife/tree/master/assets/js">GitHub</a>.</p>

<h1>Credits</h1>

<p>Thanks to <a target="_blank" href="http://utahjs.com">UtahJS</a>, Kip Lawrence and Joe Eames for their help and ideas!</p>

<p>Icons from <a target="_blank" href="http://www.webalys.com/minicons">Minicons Free Vector Icons Pack</a>.</p>
