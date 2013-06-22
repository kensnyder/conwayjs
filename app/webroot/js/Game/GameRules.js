(function(exports) {
	"use strict";
	
	/**
	 * The list of game rules to choose from
	 * @property GameRules
	 */
	exports.GameRules = [
		{
			rule: "23/3",
			name: "Conway",
			desc: "The original",
			type: "chaotic"
		},
		{
			rule: "1357/1357",
			name: "Replicator",
			desc: "A rule in which every pattern is a replicator.",
			type: "explosive"
		},
		{
			rule: "23/36",
			name: "HighLife",
			desc: "A chaotic rule very similar to Conway's Life that is of interest because it has a simple replicator.",
			type: "chaotic"
		},
		{
			rule: "23/37",
			name: "DryLife",
			desc: "An chaotic rule closely related to Conway's Life, named after the fact that the standard spaceships bigger than the glider do not function in the rule. Has a small puffer based on the R-pentomino, w",
			type: "chaotic"
		},
		{
			rule: "12345/3",
			name: "Maze",
			desc: "An expanding rule that crystalizes to form maze-like designs.",
			type: "expansive"
		},
		{
			rule: "012345678/3",
			name: "Life w/o death",
			desc: "An expanding rule that produces complex flakes. It also has important ladder patterns.",
			type: "expansive"
		},
		{
			rule: "34678/3678",
			name: "Day & Night",
			desc: "A stable rule that is symmetric under on-off reversal. Many patterns exhibiting highly complex behavior have been found for it.",
			type: "stable"
		},
		{
			rule: "023/3",
			name: "DotLife",
			desc: "A variation on Conway's Game of Life in which lone cells stay alive.",
			type: "chaotic"
		},
		{
			rule: "4567/345",
			name: "Assimilation",
			desc: "A very stable rule that forms permanent diamond-shaped patterns with partially filled interiors.",
			type: "stable"
		},
		{
			rule: "1/1",
			name: "Gnarl",
			desc: "A simple exploding rule that forms complex patterns from even a single live cell.",
			type: "explosive"
		},
		{
			rule: "02468/1357",
			name: "Fredkin",
			desc: "A rule in which, like Replicator, every pattern is a replicator.",
			type: "explosive"
		},
		{
			rule: "/2",
			name: "Seeds",
			desc: "An exploding rule in which every cell dies in every generation. It has many simple orthogonal spaceships, though it is in general difficult to create patterns that don't explode.",
			type: "explosive"
		},
		{
			rule: "0/2",
			name: "Live Free or Die",
			desc: "An exploding rule in which only cells with no neighbors survive. It has many spaceships, puffers, and oscillators, some of infinitely extensible size and period.",
			type: "explosive"
		},
		{
			rule: "/234",
			name: "Serviettes",
			desc: "An exploding rule in which every cell dies every generation (like seeds). This rule is of interest because of the fabric-like beauty of the patterns that it produces.",
			type: "explosive"
		},
		{
			rule: "1234/3",
			name: "Mazectric",
			desc: "An expanding rule that crystalizes to form maze-like designs that tend to be straighter (ie. have longer \"halls\") than the standard maze rule.",
			type: "expansive"
		},
		{
			rule: "45678/3",
			name: "Coral",
			desc: "A rule in which patterns grow slowly and form coral-like textures.",
			type: "expansive"
		},
		{
			rule: "34/34",
			name: "34 Life",
			desc: "An exploding rule that was initially thought to be a stable alternative to Conway's Life, until computer simulation found that most patterns tend to explode. It has many small oscillators and simple p",
			type: "explosive"
		},
		{
			rule: "5/345",
			name: "Long Life",
			desc: "A stable rule that gets its name from the fact that it has many simple extremely high period oscillators.",
			type: "stable"
		},
		{
			rule: "5678/35678",
			name: "Diamoeba",
			desc: "A chaotic pattern that forms large diamonds with chaotically oscillating boundaries. Known to have quadratically-growing patterns.",
			type: "chaotic"
		},
		{
			rule: "1358/357",
			name: "Amoeba",
			desc: "A chaotic rule that is well balanced between life and death; it forms patterns with chaotic interiors and wildly moving boundaries.",
			type: "chaotic"
		},
		{
			rule: "238/357",
			name: "Pseudo Life",
			desc: "A chaotic rule with evolution that resembles Conway's Life, but few patterns from Life work in this rule because the glider is unstable.",
			type: "chaotic"
		},
		{
			rule: "125/36",
			name: "2x2",
			desc: "A chaotic rule with many simple still lifes, oscillators and spaceships. Its name comes from the fact that it sends patterns made up of 2x2 blocks to patterns made up of 2x2 blocks.",
			type: "chaotic"
		},
		{
			rule: "245/368",
			name: "Move",
			desc: "A rule in which random patterns tend to stabilize extremely quickly. Has a very common slow-moving spaceship and slow-moving puffer.",
			type: "stable"
		},
		{
			rule: "235678/3678",
			name: "Stains",
			desc: "A stable rule in which most patterns tend to \"fill in\" bounded regions. Most nearby rules (such as coagulations) tend to expand.",
			type: "stable"
		},
		{
			rule: "235678/378",
			name: "Coagulations",
			desc: "An expanding rule in which patterns tend to expand forever, producing a thick \"goo\" as it does so.",
			type: "expansive"
		},
		{
			rule: "2345/45678",
			name: "Walled cities",
			desc: "A stable rule that forms centers of pseudo-random activity separated by walls.",
			type: "stable"
		},
		{
			rule: "45678/5678",
			name: "Vote",
			desc: "Standard GÃ©rard Vichniac voting rule, also known as \"Majority\", used as a model for majority voting.",
			type: "stable"
		},
		{
			rule: "35678/4678",
			name: "Vote 4/5",
			desc: "A modification of the standard GÃ©rard Vichniac voting rule, also known as \"Anneal\", used as a model for majority voting.",
			type: "stable"
		}
	];
	
}(typeof exports === 'undefined' ? this : exports));