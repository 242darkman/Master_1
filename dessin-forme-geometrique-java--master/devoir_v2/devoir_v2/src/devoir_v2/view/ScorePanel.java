package devoir_v2.view;

import javax.swing.JLabel;
import javax.swing.JPanel;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Circle;
import devoir_v2.model.Rectangle;
import devoir_v2.model.Triangle;

public class ScorePanel extends JPanel {
	private static final long serialVersionUID = 1L;
	JLabel total;
	String shape_list = "";

	public ScorePanel(ContainerShapes cs) {// constructor with the initial message
		total = new JLabel("Please place shapes to contain the points");
		this.add(total);
	}

	public void updatePanel(ContainerShapes cs, boolean won, float f) {
		if (won) { // if we won we show the Congratulations message with our score
			shape_list = ("<html>  Congratulations you won! :) <br>" + "your score is " + f * 100 + "%" + "</html>");
		} else { // if we did not win yet we show our shapes list

			shape_list = "";
			for (int i = 0; i < cs.shapes.size(); i++) {
				if (cs.shapes.get(i) instanceof Circle) {
					shape_list += "Cir ";
				}
				if (cs.shapes.get(i) instanceof Triangle) {
					shape_list += "Tri ";
				}
				if (cs.shapes.get(i) instanceof Rectangle) {
					shape_list += "Rec ";
				}
				shape_list += String.format("%.0f", (cs.shapes.get(i).surface / 1000)) + " ";
			}
			shape_list += " The number of shapes " + cs.shapes.size();

		}
		total.setText(shape_list);
		this.add(total);
	}

}
