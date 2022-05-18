import pygame as pg

from cardsPackage.suitsEnum import Suits


class CardImage:

    @staticmethod
    def load_card_image(suit, value):
        """

        :param suit:
        :param value:
        :return:
        """
        s = ""
        if suit == Suits.DIAMONDS.value:
            s = "Diamonds"
        elif suit == Suits.SPADES.value:
            s = "Spades"
        elif suit == Suits.HEARTS.value:
            s = "Hearts"
        elif suit == Suits.CLUBS.value:
            s = "Clubs"
        return pg.image.load("resources/cards/card" + s + str(value) + ".png").convert_alpha()

    @staticmethod
    def load_images(card_back, scale):
        """
        méthode permettant de charger l'image la carte retournée
        :param card_back:
        :param scale:
        :return:
        """
        CardImage.scale = scale
        CardImage.image_array = []
        for i in range(0, 4):
            CardImage.image_array.append([])
            for j in range(0, 13):
                CardImage.image_array[i].append(CardImage.scale_surface(CardImage.load_card_image(i + 1, j + 1)))
        CardImage.back_card_image = CardImage.scale_surface(
            pg.image.load("resources/cards/cardBack_" + card_back + ".png").convert_alpha())
        CardImage.pile_card_image = CardImage.scale_surface(pg.image.load("resources/cards/panel.png").convert_alpha())
        CardImage.card_width = CardImage.back_card_image.get_width()
        CardImage.card_height = CardImage.back_card_image.get_height()

    @staticmethod
    def scale_surface(surface):
        """
        méthode permettant de redimensionner nos images de cartes
        :param surface:
        :return:
        """
        return pg.transform.smoothscale(surface,
                                        (int(surface.get_width() * CardImage.scale),
                                         int(surface.get_height() * CardImage.scale)))

    @staticmethod
    def get_card_asset(suit, value, visible):
        if not visible:
            return CardImage.back_card_image
        else:
            # print(value)
            return CardImage.image_array[suit.value - 1][value - 1]


