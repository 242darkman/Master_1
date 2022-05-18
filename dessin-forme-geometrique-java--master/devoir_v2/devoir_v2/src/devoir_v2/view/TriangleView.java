package devoir_v2.view;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;

import devoir_v2.model.Shape;
import devoir_v2.model.Triangle;

public class TriangleView implements ShapeView {
	public void DrawShape(Graphics g, Shape shape) { // method that draws the Triangle
		Graphics2D g2d = (Graphics2D) g;
		Triangle t = (Triangle) shape;

		g2d.setPaint(Color.YELLOW);
		int[] x = { (int) t.points[0].getX(), (int) t.points[1].getX(), (int) t.points[2].getX() };
		int[] y = { (int) t.points[0].getY(), (int) t.points[1].getY(), (int) t.points[2].getY() };
		g2d.drawPolygon(x, y, 3);
	}
}
